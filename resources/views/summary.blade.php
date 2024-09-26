<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Analyzer Results</title>
    <style>
        body {
            margin: 50px;
        }

        .summary {
            padding: 20px 0px 20px 20px;
            font-size: 20px;
            border: 1px solid #e2e2e2;
            margin-bottom: 50px;
            line-height: 30px;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .dale-chall {
            margin-bottom: 50px;
        }

        .dale-chall-details {
            margin-bottom: 40px;
        }

        .dale-chall-details p {
            font-size: 20px;
        }

        .dale-chall-details span {
            color: red;
        }

        .dale-chall-formula {
            background-color: #f0f0f0;
            padding: 10px 20px;
            border: 1px solid #ccc;
            font-size: 20px;
            font-family: "Times New Roman", Times, serif;
        }

        .metric-item {
            color: red;
        }

        a {
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div>
        <h1>Summary</h1>
        <p class="summary">{{ $data["summary"] }}</p>
    </div>
    <div class="dale-chall">
        <h1>Dale-Chall Formula:</h1>
        <div class="dale-chall-details">
            <p>Score: <span id="score">{{ $data["score"] }}</span></p>
            <p>Reading Difficulty: <span id="readingDifficulty">{{ $data["readingDifficulty"] }}</span></p>
            <p>Grade Level: <span id="gradeLevel">{{ $data["gradeLevel"] }}</span></p>
            <p>Age Range: <span id="ageRange">{{ $data["ageRange"] }}</span></p>
        </div>
        <div>
            <span class="dale-chall-formula">
                Score = 64 - (0.95 * <span class="metric-item">{{ $data["difficultWordCount"] }}</span> difficult words / <span class="metric-item">{{ $data["wordCount"] }}</span> words * 100) - (0.69 * <span class="metric-item">{{ $data["wordCount"] }}</span> words / <span class="metric-item">{{ $data["sentenceCount"] }}</span> sentences)
            </span>
        </div>
    </div>

    <a href="{{ route('index') }}">Go Back</a>
</body>

</html>