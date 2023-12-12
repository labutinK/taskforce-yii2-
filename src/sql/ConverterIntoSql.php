<?php

namespace borpheus\sql;

use borpheus\exception\GetStringError;
use borpheus\exception\UndefinedFile;
use SplFileObject;
use DirectoryIterator;

class ConverterIntoSql
{
    private SplFileObject $fileObj;
    private string $dirPath;

    /**
     * @throws UndefinedFile
     */
    public function __construct(string $dirPath)
    {
        $this->dirPath = $dirPath;
        if (!is_dir($this->dirPath)) {
            throw new UndefinedFile('Директории не существует');
        }
    }


    /**
     * @throws GetStringError
     */
    public function convertFiles()
    {
        try {
            foreach (new DirectoryIterator($this->dirPath) as $fileInfo) {
                if ($fileInfo->getExtension() === 'csv') {
                    $file = new SplFileObject($fileInfo->getFileInfo());
                    $file->setFlags(SplFileObject::READ_CSV);
                    $this->convertOneFile($file);
                }
            }
        } catch (GetStringError $e) {
            print($e->getMessage());
        }

    }

    /**
     * @throws GetStringError
     */
    private function convertOneFile($file)
    {
        $columns = $this->getColumnsNames($file);
        $newFileName = pathinfo($file->getFilename())['filename'] . '.sql';
        $path = $this->dirPath . '/' . $newFileName;
        foreach ($this->getNextLine($file, $columns) as $sqlLine) {
            if (!file_put_contents("$path", $sqlLine, FILE_APPEND)) {
                throw new \borpheus\exception\GetStringError("Не удалось записать в файл $newFileName");
            }
        }
        print("Файл $newFileName создан \n");

    }

    /**
     * @throws GetStringError
     */
    private function getNextLine($file, $columns): \Generator
    {
        while (!$file->eof()) {
            if (!$newLine = $file->fgetcsv()) {
                throw new \borpheus\exception\GetStringError('Не удается получить строку');
            }
            if (count($newLine) == count($columns)) {
                yield $this->getRequestSqlForLine($newLine, $columns, pathinfo($file->getFilename())['filename']);
            }
        }
    }

    private function getRequestSqlForLine($line, $columns, $tableName): string
    {
        $sql = "INSERT INTO $tableName (";
        foreach ($columns as $column) {
            $column = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $column);
            $sql .= "$column";
            if (end($columns) !== $column) {
                $sql .= ', ';
            }

        }
        $sql .= ') VALUES (';

        foreach ($line as $cell) {
            $cell = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $cell);
            $sql .= "'$cell'";
            if (end($line) !== $cell) {
                $sql .= ', ';
            }
        }
        $sql .= ");\n";
        return $sql;
    }

    private function getColumnsNames($file)
    {
        return ($file->fgetcsv());
    }
}
