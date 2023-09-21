<?php

declare(strict_types=1);

namespace Tests\PhpOffice\Math\Writer;

use DOMDocument;
use PHPUnit\Framework\TestCase;

class WriterTestCase extends TestCase
{
    public function assertIsSchemaMathMLValid(string $content): void
    {
        $dom = new DOMDocument();
        $dom->loadXML($content);
        $xmlSource = $dom->saveXML();

        $dom->loadXML($xmlSource);
        $dom->schemaValidate(dirname(__DIR__, 2) . '/resources/schema/mathml3/mathml3.xsd');

        $error = libxml_get_last_error();
        if ($error instanceof LibXMLError) {
            $this->failXmlError($error, $fileName, $xmlSource);
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * @param array<string, string> $params
     */
    protected function failXmlError(LibXMLError $error, string $fileName, string $source, array $params = []): void
    {
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $errorType = 'warning';
                break;
            case LIBXML_ERR_ERROR:
                $errorType = 'error';
                break;
            case LIBXML_ERR_FATAL:
                $errorType = 'fatal';
                break;
            default:
                $errorType = 'Error';
                break;
        }
        $errorLine = (int) $error->line;
        $contents = explode("\n", $source);
        $lines = [];
        if (isset($contents[$errorLine - 2])) {
            $lines[] = '>> ' . $contents[$errorLine - 2];
        }
        if (isset($contents[$errorLine - 1])) {
            $lines[] = '>>> ' . $contents[$errorLine - 1];
        }
        if (isset($contents[$errorLine])) {
            $lines[] = '>> ' . $contents[$errorLine];
        }
        $paramStr = '';
        if (!empty($params)) {
            $paramStr .= "\n" . ' - Parameters :' . "\n";
            foreach ($params as $key => $val) {
                $paramStr .= '   - ' . $key . ' : ' . $val . "\n";
            }
        }
        $this->fail(sprintf(
            "Validation %s :\n - File : %s\n - Line : %s\n - Message : %s - Lines :\n%s%s",
            $errorType,
            $fileName,
            $error->line,
            $error->message,
            implode(PHP_EOL, $lines),
            $paramStr
        ));
    }
}
