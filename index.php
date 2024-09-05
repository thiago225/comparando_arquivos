<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparador de Planilhas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Comparador de Planilhas</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="file1" class="form-label">Carregar Planilha 1</label>
                <input type="file" class="form-control" id="file1" name="file1" required>
            </div>
            <div class="mb-3">
                <label for="sheetName1" class="form-label">Nome da Aba na Planilha 1</label>
                <input type="text" class="form-control" id="sheetName1" name="sheetName1" placeholder="Ex: Base Mottu" required>
            </div>
            <div class="mb-3">
                <label for="columnName1" class="form-label">Nome da Coluna de Identificação na Planilha 1</label>
                <input type="text" class="form-control" id="columnName1" name="columnName1" placeholder="Ex: J" required>
            </div>
            <div class="mb-3">
                <label for="columnFilial1" class="form-label">Coluna de Identificação na Planilha 1</label>
                <input type="text" class="form-control" id="columnFilial1" name="columnFilial1" placeholder="Ex: A" required>
            </div>
            <hr>
            <div class="mb-3">
                <label for="file2" class="form-label">Carregar Planilha 2</label>
                <input type="file" class="form-control" id="file2" name="file2" required>
            </div>
            <div class="mb-3">
                <label for="sheetName2" class="form-label">Nome da Aba na Planilha 2</label>
                <input type="text" class="form-control" id="sheetName2" name="sheetName2" placeholder="Ex: Aba1" required>
            </div>
            <div class="mb-3">
                <label for="columnName2" class="form-label">Nome da Coluna de Identificação na Planilha 2</label>
                <input type="text" class="form-control" id="columnName2" name="columnName2" placeholder="Ex: N° Sol. PSD" required>
            </div>
            <div class="mb-3">
                <label for="columnFilial2" class="form-label">Coluna de Identificação na Planilha 2</label>
                <input type="text" class="form-control" id="columnFilial2" name="columnFilial2" placeholder="Ex: A" required>
            </div>
            <button type="submit" id="submit" class="btn btn-primary">Comparar Planilhas</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="src/js/index.js"> </script>
</body>

</html>