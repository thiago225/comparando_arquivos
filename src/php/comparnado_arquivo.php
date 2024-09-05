<?php

require __DIR__ . '/../../composer/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebendo os dados do formulário
    $sheetName1 = $_POST['sheetName1'];
    $columnName1 = $_POST['columnName1'];
    $columnFilial1 = $_POST['columnFilial1'];

    $sheetName2 = $_POST['sheetName2'];
    $columnName2 = $_POST['columnName2'];
    $columnFilial2 = $_POST['columnFilial2'];

    $file1 = $_FILES['file1']['tmp_name'];
    $file2 = $_FILES['file2']['tmp_name'];

    // Carregando as planilhas
    $spreadsheet1 = IOFactory::load($file1);
    $spreadsheet2 = IOFactory::load($file2);

    // Selecionando as abas específicas
    $sheetParceiro = $spreadsheet1->getSheetByName($sheetName1);
    $sheetAtivmob = $spreadsheet2->getSheetByName($sheetName2);

    // Verificando se as abas foram encontradas
    if ($sheetParceiro === null) {
        die("A aba '$sheetName1' não foi encontrada na primeira planilha.");
    }
    if ($sheetAtivmob === null) {
        die("A aba '$sheetName2' não foi encontrada na segunda planilha.");
    }

    // Obtendo o número de linhas de ambas as planilhas
    $rowCountParceiro = $sheetParceiro->getHighestRow();
    $rowCountAtivmob = $sheetAtivmob->getHighestRow();

    // Inicializando arrays para armazenar os valores
    $colunaParceiro = [];
    $colunaAtivmob = [];

    // Iterando sobre as linhas e extraindo os valores das colunas específicas
    for ($row = 1; $row <= $rowCountParceiro; $row++) {
        $pedido = $sheetParceiro->getCell($columnName1 . $row)->getValue();
        $filial = $sheetParceiro->getCell($columnFilial1 . $row)->getValue(); // Coluna 'M' como exemplo
        if ($pedido !== null) {
            $colunaParceiro[] = array("delivery_id" => $pedido, "filial" => $filial);
        }
    }

    for ($row = 1; $row <= $rowCountAtivmob; $row++) {
        $solPsd = $sheetAtivmob->getCell($columnName2 . $row)->getValue();
        $filial = $sheetAtivmob->getCell($columnFilial2 . $row)->getValue(); // Coluna 'A' como exemplo

        if ($solPsd !== null) {
            $colunaAtivmob[] = array("delivery_id" => $solPsd, "filial" => $filial);
        }
    }

    // Comparação e geração dos resultados
    $arr_resultado = [];
    $arr_resultado_p = [];

    // Transformar $colunaAtivmob em um mapa usando 'delivery_id' como chave
    $mapaAtivmob = [];
    // Exemplo de como tratar a conversão para tipo apropriado
    foreach ($colunaAtivmob as $value) {
        $delivery_id = (string) $value['delivery_id']; // Convertendo para string
        $mapaAtivmob[$delivery_id] = $value['filial'];
    }

    // Comparar $colunaParceiro com o mapa
    foreach ($colunaParceiro as $info) {
        $delivery_id = (string) $info['delivery_id']; // Convertendo para string
        if (!isset($mapaAtivmob[$delivery_id])) {
            $arr_resultado[] = array("delivery_id" => $info['delivery_id'], "filial" => $info['filial']);
        }
    }
    // O que tem na planilha do Ativmob mas não tem no parceiro
    $mapaParceiro = [];
    foreach ($colunaParceiro as $value) {
        $delivery_id = (string) $value['delivery_id'];
        $mapaParceiro[$delivery_id] = $value['filial'];
    }

    foreach ($colunaAtivmob as $info) {
        $delivery_id = (string) $info['delivery_id']; // Convertendo para string
        if (!isset($mapaParceiro[$delivery_id])) {
            $arr_resultado_p[] = array("delivery_id" => $info['delivery_id'], "filial" => $info['filial']);
        }
    }
    // Criar uma nova planilha
    $spreadsheet = new Spreadsheet();

    // Adicionar dados de $arr_resultado
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('Diferencas Parceiro');

    $sheet1->setCellValue('A1', 'delivery_id');
    $sheet1->setCellValue('B1', 'filial');

    $row = 2;
    foreach ($arr_resultado as $result) {
        $sheet1->setCellValue('A' . $row, $result['delivery_id']);
        $sheet1->setCellValue('B' . $row, $result['filial']);
        $row++;
    }

    // Adicionar nova aba para $arr_resultado_p
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Diferencas Ativmob');

    $sheet2->setCellValue('A1', 'delivery_id');
    $sheet2->setCellValue('B1', 'filial');

    $row = 2;
    foreach ($arr_resultado_p as $result) {
        $sheet2->setCellValue('A' . $row, $result['delivery_id']);
        $sheet2->setCellValue('B' . $row, $result['filial']);
        $row++;
    }

    // Salvar o arquivo Excel
    $filename = 'resultado_comparacao_' . time() . '.xlsx';
    $filepath = __DIR__ . '/' . $filename;
    $writer = new Xlsx($spreadsheet);
    $writer->save($filepath);

    // Retornar o caminho do arquivo para o JavaScript
    $filepath = __DIR__ . '/' . $filename;
    echo json_encode(['file' => $filename, 'msg' => 'Arquivo gerado', 'filepath' => $filepath]);

    // var_dump(['file' => $filename, "msg" => "enviou"]);
}
