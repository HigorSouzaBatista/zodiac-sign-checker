<?php include('layouts/header.php'); ?>

<div class="container mt-5">
    <h1 class="text-center">Seu Signo Zodiacal</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["data_nascimento"])) {
        $data_nascimento_str = $_POST["data_nascimento"];
        $data_nascimento = new DateTime($data_nascimento_str);

        $signos = simplexml_load_file("signos.xml");
        $signo_encontrado = null;

        foreach ($signos->signo as $signo) {
            $dataInicio_str = $signo->dataInicio . '/' . $data_nascimento->format('Y');
            $dataFim_str = $signo->dataFim . '/' . $data_nascimento->format('Y');

            $dataInicio = DateTime::createFromFormat('d/m/Y', $dataInicio_str);
            $dataFim = DateTime::createFromFormat('d/m/Y', $dataFim_str);

            // Ajuste para signos que cruzam o ano (ex: Capricórnio, Sagitário)
            if ($dataInicio > $dataFim) {
                if ($data_nascimento >= $dataInicio || $data_nascimento <= $dataFim) {
                    $signo_encontrado = $signo;
                    break;
                }
            } else {
                if ($data_nascimento >= $dataInicio && $data_nascimento <= $dataFim) {
                    $signo_encontrado = $signo;
                    break;
                }
            }
        }

        if ($signo_encontrado) {
            echo "<div class='card mt-4'>";
            echo "<div class='card-body'>";
            echo "<h2 class='card-title'>" . $signo_encontrado->signoNome . "</h2>";
            echo "<p class='card-text'>" . $signo_encontrado->descricao . "</p>";
            echo "<p class='card-text'><small class='text-muted'>Período: " . $signo_encontrado->dataInicio . " a " . $signo_encontrado->dataFim . "</small></p>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<p class='alert alert-warning mt-4'>Não foi possível encontrar seu signo. Verifique a data informada.</p>";
        }
    } else {
        echo "<p class='alert alert-info mt-4'>Por favor, insira sua data de nascimento na página inicial.</p>";
    }
    ?>
    <p class="mt-4"><a href="index.php" class="btn btn-secondary">Voltar</a></p>
</div>

<?php include('layouts/footer.php'); ?>
