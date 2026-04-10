<?php

namespace App\Services;

use App\Models\CobroPacifico;
use Illuminate\Support\Collection;

class PacificoFileService
{
    public function generateLine(CobroPacifico $cobro): string
    {
        $fields = [];

        // Campo 1: Indicador de registro (1)
        $fields[] = $this->padRight('1', 1);
        // Campo 2: Código de transacción (3)
        $fields[] = $this->padRight($cobro->transaccion ?? 'OCP', 3);
        // Campo 3: Código de servicio (2)
        $fields[] = $this->padRight($cobro->codigo_servicio ?? 'ZG', 2);
        // Campo 4: Número de cuenta (10)
        $fields[] = $this->padRight($this->padAccountNumber($cobro->numero_cuenta), 10);
        // Campo 5: Valor (15)
        $fields[] = $this->padLeft(number_format($cobro->valor, 2, '', ''), 15, '0');
        // Campo 6: Código de tercero (15)
        $fields[] = $this->padLeft($cobro->codigo_tercero ?? '', 15, ' ');
        // Campo 7: Referencia (20)
        $fields[] = $this->padRight($cobro->referencia, 20);
        // Campo 8: Forma de pago (2)
        $fields[] = $this->padRight($cobro->forma_pago ?? 'RE', 2);
        // Campo 9: Moneda (3)
        $fields[] = $this->padRight($cobro->moneda ?? 'USD', 3);
        // Campo 10: Nombre del tercero (30)
        $fields[] = $this->padRight($cobro->nombre_tercero ?? '', 30);
        // Campo 11: Código de localidad (2)
        $fields[] = $this->padRight('', 2);
        // Campo 12: Reserved (2)
        $fields[] = $this->padRight('', 2);
        // Campo 13: Tipo de identificación (1)
        $fields[] = $this->padRight($cobro->tipo_id_tercero, 1);
        // Campo 14: Identificación (14)
        $fields[] = $this->padLeft($cobro->identificacion ?? '', 14, ' ');
        // Campo 15: Reserved (83)
        $fields[] = $this->padRight('', 83);
        // Campo 16: Valor IVA servicios (9)
        $fields[] = $this->padLeft(number_format($cobro->valor_iva_servicios ?? 0, 2, '', ''), 9, '0');
        // Campo 17: Tipo de prestación (1)
        $fields[] = $this->padRight($cobro->tipo_prestacion ?? 'A', 1);
        // Campo 18: Valor IVA bienes (9)
        $fields[] = $this->padLeft(number_format($cobro->valor_iva_bienes ?? 0, 2, '', ''), 9, '0');
        // Campo 19: Base imponible servicios (10)
        $fields[] = $this->padLeft(number_format($cobro->base_imponible_servicios ?? 0, 2, '', ''), 10, '0');
        // Campo 20: Base imponible bienes (10)
        $fields[] = $this->padLeft(number_format($cobro->base_imponible_bienes ?? 0, 2, '', ''), 10, '0');

        // Suma total: 1+3+2+10+15+15+20+2+3+30+2+2+1+14+83+9+1+9+10+10 = 242
        $line = implode('', $fields);

        return $this->normalizeLine($line);
    }

    private function normalizeLine(string $line): string
    {
        $line = $this->convertToAscii($line);

        if (strlen($line) < $this->getExpectedLength()) {
            $line = str_pad($line, $this->getExpectedLength(), ' ', STR_PAD_RIGHT);
        } elseif (strlen($line) > $this->getExpectedLength()) {
            $line = substr($line, 0, $this->getExpectedLength());
        }

        if (!$this->validateLineLength($line)) {
            throw new \RuntimeException('Invalid line length after ASCII conversion; each line must be ' . $this->getExpectedLength() . ' chars. Actual: ' . strlen($line));
        }

        return $line;
    }

    public function generateFile(Collection $cobros): string
    {
        $content = '';
        foreach ($cobros as $cobro) {
            $content .= $this->generateLine($cobro) . "\n";
        }
        return $content;
    }

    public function generateAndDownload(Collection $cobros, string $filename = 'cobros_pacifico.txt'): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $content = $this->generateFile($cobros);
        $asciiContent = $this->convertToAscii($content);
        $normalized = $this->normalizeFileLines($asciiContent);

        return response()->streamDownload(
            function () use ($normalized) {
                echo $normalized;
            },
            $filename,
            [
                'Content-Type' => 'text/plain; charset=us-ascii',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    private function convertToAscii(string $content): string
    {
        $map = [
            'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ü'=>'U','Ñ'=>'N',
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
            'À'=>'A','È'=>'E','Ì'=>'I','Ò'=>'O','Ù'=>'U','à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
            'Â'=>'A','Ê'=>'E','Î'=>'I','Ô'=>'O','Û'=>'U','â'=>'a','ê'=>'e','î'=>'i','ô'=>'o','û'=>'u',
            'Ä'=>'A','Ë'=>'E','Ï'=>'I','Ö'=>'O','Ÿ'=>'Y','ä'=>'a','ë'=>'e','ï'=>'i','ö'=>'o','ÿ'=>'y',
            'Ç'=>'C','ç'=>'c'
        ];

        $content = strtr($content, $map);

        // Convertir a ASCII extrayendo los caracteres fuera de rango como espacios para mantener longitud fija
        $content = preg_replace('/[^\x00-\x7F]/', ' ', $content);

        return $content;
    }

    private function padRight(string $value, int $length, string $padChar = ' '): string
    {
        return str_pad(substr($value, 0, $length), $length, $padChar, STR_PAD_RIGHT);
    }

    private function padLeft(string $value, int $length, string $padChar = '0'): string
    {
        return str_pad(substr($value, 0, $length), $length, $padChar, STR_PAD_LEFT);
    }

    private function padAccountNumber(?string $numero): string
    {
        if (empty($numero)) {
            return str_repeat('0', 10);
        }
        return str_pad(substr($numero, 0, 10), 10, '0', STR_PAD_LEFT);
    }

    public function validateLineLength(string $line): bool
    {
        return strlen($line) === $this->getExpectedLength();
    }

    public function validateFileLines(string $content): bool
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($content, "\r\n"));
        foreach ($lines as $line) {
            if (strlen($line) !== 242) {
                return false;
            }
        }
        return true;
    }

    private function normalizeFileLines(string $content): string
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($content, "\r\n"));
        $normalized = [];

        foreach ($lines as $line) {
            if (strlen($line) < 242) {
                $line = str_pad($line, 242, ' ', STR_PAD_RIGHT);
            } elseif (strlen($line) > 242) {
                $line = substr($line, 0, 242);
            }
            $normalized[] = $line;
        }

        return implode("\n", $normalized) . "\n";
    }

    public function getExpectedLength(): int
    {
        return 242;
    }
}
