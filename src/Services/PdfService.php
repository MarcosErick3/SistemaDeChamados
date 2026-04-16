<?php

class PdfService
{
    public function gerarPdfChamado(Chamado $chamado)
    {
        $directory = __DIR__ . '/../../public/pdfs';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName = 'chamado_' . $chamado->getId() . '.pdf';
        $filePath = $directory . '/' . $fileName;

        // Versão simplificada sem dependências externas
        // Cria um arquivo PDF básico com texto simples
        $content = $this->gerarPdfSimples($chamado);
        file_put_contents($filePath, $content);

        return 'pdfs/' . $fileName;
    }

    private function gerarPdfSimples(Chamado $chamado)
    {
        // PDF básico com cabeçalho mínimo
        $pdf = "%PDF-1.4\n";
        $pdf .= "1 0 obj\n";
        $pdf .= "<< /Type /Catalog /Pages 2 0 R >>\n";
        $pdf .= "endobj\n";

        $pdf .= "2 0 obj\n";
        $pdf .= "<< /Type /Pages /Kids [3 0 R] /Count 1 >>\n";
        $pdf .= "endobj\n";

        $pdf .= "3 0 obj\n";
        $pdf .= "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R >>\n";
        $pdf .= "endobj\n";

        $pdf .= "4 0 obj\n";
        $pdf .= "<< /Length 200 >>\n";
        $pdf .= "stream\n";
        $pdf .= "BT\n";
        $pdf .= "/F1 12 Tf\n";
        $pdf .= "50 750 Td\n";
        $pdf .= "(Chamado #" . str_pad($chamado->getId(), 5, '0', STR_PAD_LEFT) . ") Tj\n";
        $pdf .= "0 -20 Td\n";
        $pdf .= "(Sistema de Chamados - Gerado automaticamente) Tj\n";
        $pdf .= "ET\n";
        $pdf .= "endstream\n";
        $pdf .= "endobj\n";

        $pdf .= "xref\n";
        $pdf .= "0 5\n";
        $pdf .= "0000000000 65535 f \n";
        $pdf .= "0000000009 00000 n \n";
        $pdf .= "0000000058 00000 n \n";
        $pdf .= "0000000115 00000 n \n";
        $pdf .= "0000000200 00000 n \n";

        $pdf .= "trailer\n";
        $pdf .= "<< /Size 5 /Root 1 0 R >>\n";
        $pdf .= "startxref\n";
        $pdf .= strlen($pdf) . "\n";
        $pdf .= "%%EOF";

        return $pdf;
    }
}
