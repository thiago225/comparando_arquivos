document.getElementById('submit').addEventListener('click', function () {
    event.preventDefault();

    const formData = new FormData();
    console.log("entrou aqui");

    formData.append('sheetName1', document.getElementById('sheetName1').value);
    formData.append('columnName1', document.getElementById('columnName1').value);
    formData.append('columnFilial1', document.getElementById('columnFilial1').value);

    formData.append('sheetName2', document.getElementById('sheetName2').value);
    formData.append('columnName2', document.getElementById('columnName2').value);
    formData.append('columnFilial2', document.getElementById('columnFilial2').value);
    
    formData.append('file1', document.getElementById('file1').files[0]);
    formData.append('file2', document.getElementById('file2').files[0]);

    // console.log(data);
    fetch('http://localhost/projetos_trabalho/comprarando_planilha/src/php/comparnado_arquivo.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.file) {
                const link = document.createElement('a');
                link.href = 'http://localhost/projetos_trabalho/comprarando_planilha/src/php/' + data.file; // Ajuste o caminho se necessário
                link.download = data.file;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert('Erro ao processar os arquivos.');
            }
        })
});
