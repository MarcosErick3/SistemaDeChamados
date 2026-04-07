<?php

class PdfService
{
    private const PAGE_WIDTH = 595.28;
    private const PAGE_HEIGHT = 841.89;
    private const MARGIN_LEFT = 50;
    private const MARGIN_TOP = 790;
    private const MARGIN_BOTTOM = 60;

    public function gerarPdfChamado(Chamado $chamado)
    {
        $directory = __DIR__ . '/../../public/pdfs';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'chamado_' . $chamado->getId() . '.pdf';
        $filePath = $directory . '/' . $fileName;
        $pdfContent = $this->montarPdf($this->montarLinhas($chamado));

        if (file_put_contents($filePath, $pdfContent) === false) {
            throw new RuntimeException('Nao foi possivel gerar o PDF do chamado.');
        }

        return 'pdfs/' . $fileName;
    }

    private function montarLinhas(Chamado $chamado)
    {
        $telefone = trim(sprintf(
            '%s %s',
            $chamado->getDddUsuario() ? '(' . $chamado->getDddUsuario() . ')' : '',
            $chamado->getTelefoneUsuario() ?? ''
        ));

        $cidadeUf = trim(($chamado->getCidade() ?? '') . ' ' . ($chamado->getUf() ?? ''));
        $dataAbertura = $chamado->getDataAbertura() ?: date('d/m/Y H:i');

        return array_merge(
            [
                ['text' => 'Chamado #' . str_pad((string) $chamado->getId(), 5, '0', STR_PAD_LEFT), 'font' => 'F2', 'size' => 16],
                ['text' => '', 'font' => 'F1', 'size' => 12],
                ['text' => 'Detalhes do Chamado', 'font' => 'F2', 'size' => 12],
            ],
            $this->montarCampoTexto('Equipamento', $chamado->getEquipamento()),
            $this->montarCampoTexto('Numero Serie', $chamado->getNumeroSerie()),
            $this->montarCampoTexto('Numero Patrimonio', $chamado->getNumeroPatrimonio()),
            $this->montarCampoTexto('Local', $chamado->getLocal()),
            $this->montarCampoTexto('Unidade', $chamado->getUnidade()),
            $this->montarCampoTexto('Cidade/UF', $cidadeUf),
            $this->montarCampoTexto('Tecnico', $chamado->getTecnico()),
            $this->montarCampoTexto('Prioridade', $chamado->getPrioridade()),
            $this->montarCampoTexto('Assunto', $chamado->getAssunto()),
            $this->montarCampoTexto('Descricao', $chamado->getDescricao()),
            [
                ['text' => '', 'font' => 'F1', 'size' => 12],
                ['text' => 'Dados do Usuario', 'font' => 'F2', 'size' => 12],
            ],
            $this->montarCampoTexto('Nome', $chamado->getNomeUsuario()),
            $this->montarCampoTexto('Email', $chamado->getEmailUsuario()),
            $this->montarCampoTexto('Telefone', $telefone),
            $this->montarCampoTexto('Data Abertura', $dataAbertura),
            [
                ['text' => '', 'font' => 'F1', 'size' => 12],
                ['text' => 'Relatorio do Tecnico', 'font' => 'F2', 'size' => 12],
            ],
            $this->montarCampoTexto('Data Atendimento', $chamado->getDataAtendimento()),
            $this->montarCampoTexto('Solucao', $chamado->getSolucao()),
            $this->montarCampoTexto('Assinatura', '_________________________________')
        );
    }

    private function montarCampoTexto($label, $value)
    {
        $content = $this->normalizarTexto($value);

        if ($content === '') {
            return [
                ['text' => $label . ': -', 'font' => 'F1', 'size' => 11],
            ];
        }

        $wrapped = wordwrap($label . ': ' . $content, 88, "\n", true);
        $parts = preg_split("/\r\n|\r|\n/", $wrapped) ?: [];
        $lines = [];

        foreach ($parts as $part) {
            $lines[] = ['text' => $part, 'font' => 'F1', 'size' => 11];
        }

        return $lines;
    }

    private function normalizarTexto($value)
    {
        $text = trim((string) ($value ?? ''));
        return preg_replace('/\s+/', ' ', $text) ?? '';
    }

    private function montarPdf($lines)
    {
        $pages = $this->paginarLinhas($lines);
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
        ];

        $pageObjectNumbers = [];
        $nextObjectNumber = 5;

        foreach ($pages as $page) {
            $pageObjectNumber = $nextObjectNumber++;
            $contentObjectNumber = $nextObjectNumber++;
            $pageObjectNumbers[] = $pageObjectNumber;

            $stream = $this->montarFluxoPagina($page);

            $objects[$pageObjectNumber] = sprintf(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2F %.2F] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents %d 0 R >>',
                self::PAGE_WIDTH,
                self::PAGE_HEIGHT,
                $contentObjectNumber
            );
            $objects[$contentObjectNumber] = "<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "endstream";
        }

        $kids = implode(' ', array_map(static function ($number) {
            return $number . ' 0 R';
        }, $pageObjectNumbers));

        $objects[2] = '<< /Type /Pages /Kids [' . $kids . '] /Count ' . count($pageObjectNumbers) . ' >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];

        foreach ($objects as $number => $object) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n";
        $pdf .= '0 ' . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n";
        $pdf .= '<< /Size ' . (count($objects) + 1) . ' /Root 1 0 R >>' . "\n";
        $pdf .= "startxref\n";
        $pdf .= $xrefOffset . "\n";
        $pdf .= '%%EOF';

        return $pdf;
    }

    private function paginarLinhas($lines)
    {
        $pages = [[]];
        $currentPage = 0;
        $currentY = self::MARGIN_TOP;

        foreach ($lines as $line) {
            $lineHeight = $line['text'] === '' ? 12 : $line['size'] + 6;

            if ($currentY - $lineHeight < self::MARGIN_BOTTOM) {
                $pages[] = [];
                $currentPage++;
                $currentY = self::MARGIN_TOP;
            }

            if ($line['text'] !== '') {
                $pages[$currentPage][] = [
                    'text' => $line['text'],
                    'font' => $line['font'],
                    'size' => $line['size'],
                    'y' => $currentY,
                ];
            }

            $currentY -= $lineHeight;
        }

        return $pages;
    }

    private function montarFluxoPagina($page)
    {
        $commands = [];

        foreach ($page as $line) {
            $commands[] = 'BT';
            $commands[] = sprintf('/%s %d Tf', $line['font'], $line['size']);
            $commands[] = sprintf('1 0 0 1 %.2F %.2F Tm', self::MARGIN_LEFT, $line['y']);
            $commands[] = '(' . $this->escaparTextoPdf($line['text']) . ') Tj';
            $commands[] = 'ET';
        }

        return implode("\n", $commands) . "\n";
    }

    private function escaparTextoPdf($text)
    {
        $encoded = $text;

        if (function_exists('iconv')) {
            $converted = iconv('UTF-8', 'windows-1252//TRANSLIT', $text);
            if ($converted !== false) {
                $encoded = $converted;
            }
        }

        $encoded = str_replace('\\', '\\\\', $encoded);
        $encoded = str_replace('(', '\\(', $encoded);
        $encoded = str_replace(')', '\\)', $encoded);

        return str_replace(["\r", "\n"], '', $encoded);
    }
}
