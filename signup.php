<?php
session_start();

// Save the skill level
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['skill_level'])) {
        $_SESSION['skill_level'] = $_POST['skill_level'];
        header('Location: signup2.php'); // Redirect to second page
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chess Skill Level</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            background-color: #1d1d1d;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 30px;
            font-weight: normal;
        }

        .options {
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        .level-btn {
            background-color: #333;
            color: #ccc;
            padding: 15px 30px;
            font-size: 1rem;
            border: 2px solid transparent;
            border-radius: 10px;
            cursor: pointer;
            width: 80%;
            transition: all 0.3s ease;
            display: grid;
            grid-template-columns: 40px 1fr;
            align-items: center;
            text-align: left;
            justify-content: center;
            padding-left: 60px;
            padding-right: 60px;
        }

        .level-btn:hover {
            background-color: #444;
        }

        .level-btn.selected {
            background-color: #333;
            color: #C9A23E;
            border-color: #C9A23E;
            box-shadow: 0 0 10px rgba(201, 162, 62, 0.5);
        }

        .continue-btn {
            background-color: #C9A23E;
            color: white;
            padding: 15px 30px;
            font-size: 1.1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            width: 80%;
            transition: background-color 0.3s ease;
        }

        .continue-btn:hover {
            background-color: #B28C30;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #C9A23E;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            font-weight: normal;
        }

        .back-btn:hover {
            background-color: #B28C30;
        }
    </style>
</head>
<body>
    <button class="back-btn" onclick="window.location.href='index.html'">← Back</button>
    <div class="container">
        <h1>What Is Your Chess Skill Level?</h1>
        <form method="POST">
            <input type="hidden" name="skill_level" id="selectedLevel">
            <div class="options">
                <button type="button" class="level-btn" id="newToChess" onclick="selectLevel('newToChess')">
                    <span class="chess-piece">♟</span>
                    New to Chess
                </button>
                <button type="button" class="level-btn" id="beginner" onclick="selectLevel('beginner')">
                    <span class="chess-piece">♞</span>
                    Beginner
                </button>
                <button type="button" class="level-btn" id="intermediate" onclick="selectLevel('intermediate')">
                    <span class="chess-piece">♜</span>
                    Intermediate
                </button>
                <button type="button" class="level-btn" id="advanced" onclick="selectLevel('advanced')">
                    <span class="chess-piece">♛</span>
                    Advanced
                </button>
            </div>
            <button type="submit" class="continue-btn">CONTINUE</button>
        </form>
    </div>

    <script>
        let selectedLevel = null;

        function selectLevel(level) {
            document.querySelectorAll('.level-btn').forEach(btn => btn.classList.remove('selected'));
            document.getElementById(level).classList.add('selected');
            selectedLevel = level;
            document.querySelector('input[name="skill_level"]').value = level;
        }
    </script>
</body>
</html>
