<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Analyzer</title>
    <style>
        body {
            margin: 20px;
        }

        textarea {
            width: 90%;
            height: 200px;
            margin-bottom: 5px;
            font-family: "Times New Roman", Times, serif;
            padding: 10px;
            font-size: 16px;
        }

        .count-item {
            margin-right: 20px;
        }

        .calculate-button {
            background: #2B95FF;
            margin-top: 30px;
            padding: 10px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            color: #FFF;
            border-radius: 5px;
        }

        .calculate-button:hover {
            background: #5cc1ff;
        }

        .dale-chall {
            margin-bottom: 40px;
        }

        .dale-chall p {
            font-size: 20px;
        }

        .dale-chall span {
            color: red;
        }

        .submit-button {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #FFF;
            background-color: #4CAF50;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s;
            border: none;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #45a049;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>

</head>

<body>
    <h1>Text Analyzer</h1>

    <form id="textForm" method="POST" action="{{ route('text.analyze') }}">
        @csrf
        <textarea id="textInput" name="textInput" oninput="updateCounts()" placeholder="Enter your text here..."></textarea>

        <div class="counts">
            <span class="count-item">Word Count: <span id="wordCount">0</span></span>
            <span class="count-item">Sentence Count: <span id="sentenceCount">0</span></span>
        </div>

        <button type="button" class="calculate-button" onclick="updateTextReadability()">Click here to refresh Text Readability</button>

        <div class="dale-chall">
            <p>Score: <span id="score"></span></p>
            <p>Reading Difficulty: <span id="readingDifficulty"></span></p>
            <p>Grade Level: <span id="gradeLevel"></span></p>
            <p>Age Range: <span id="ageRange"></span></p>
        </div>

        <button type="submit" class="submit-button">Submit</button>
    </form>

    <!-- Dale-Chall word list from https://readabilityformulas.com/word-lists/the-dale-chall-word-list-for-readability-formulas/ -->
    <script src="assets/js/daleChallWords.js"></script>

    <script>
        // function to get word count and sentence count from text
        const getCounts = (text) => {
            // count words in text
            const wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;

            // abbreviations that include "."
            const abbreviations = ["a.m.", "p.m.", "mr.", "mrs.", "ms.", "dr.", "u.s.a.", "u.s.", "d.c.", "o.k.", "e.g.", "i.e."];
            // relace the abbreviations with ### to correctly count sentences
            const abbrevPattern = new RegExp(`\\b(${abbreviations.join('|')})`, 'gi');
            const modifiedText = text.toLowerCase().replace(abbrevPattern, "###");
            // count sentences in text using !.?
            const sentenceCount = modifiedText.split(/(?<=[!.?])\s+/).filter(sentence => sentence.trim().length > 0).length;

            return {
                wordCount,
                sentenceCount
            }
        }

        // function called to update the word count and sentence count as the user types in textarea.
        const updateCounts = () => {
            const text = document.getElementById('textInput').value;

            const counts = getCounts(text);

            document.getElementById('wordCount').innerText = counts.wordCount;
            document.getElementById('sentenceCount').innerText = counts.sentenceCount;
        }

        // function to get Dale-Chall Formula results using a score
        const getDaleChallDetails = (score) => {
            const scoreRanges = [{
                    min: 58,
                    max: Infinity,
                    difficulty: "Extremely Easy",
                    age: "5 - 6 years",
                    grade: "1"
                },
                {
                    min: 54,
                    max: 57,
                    difficulty: "Very Easy",
                    age: "7 years",
                    grade: "2"
                },
                {
                    min: 50,
                    max: 53,
                    difficulty: "Fairly Easy",
                    age: "8 years",
                    grade: "3"
                },
                {
                    min: 45,
                    max: 49,
                    difficulty: "Easy",
                    age: "9 years",
                    grade: "4"
                },
                {
                    min: 40,
                    max: 44,
                    difficulty: "Average",
                    age: "10 - 11 years",
                    grade: "5 - 6"
                },
                {
                    min: 34,
                    max: 39,
                    difficulty: "Average – Slightly Difficult",
                    age: "12 -13 years",
                    grade: "7 - 8"
                },
                {
                    min: 28,
                    max: 33,
                    difficulty: "Slightly Difficult",
                    age: "14 - 15 years",
                    grade: "9 - 10"
                },
                {
                    min: 22,
                    max: 27,
                    difficulty: "Fairly Difficult",
                    age: "16 - 17 years",
                    grade: "11 - 12"
                },
                {
                    min: 16,
                    max: 21,
                    difficulty: "Difficult",
                    age: "16 - 17 years",
                    grade: "13 - 15"
                },
                {
                    min: -Infinity,
                    max: 15,
                    difficulty: "Very Difficult",
                    age: "18+ years",
                    grade: "College Graduate"
                },
            ];

            for (const range of scoreRanges) {
                if (Math.floor(score) >= range.min && Math.floor(score) <= range.max) {
                    return {
                        ...range,
                        score: score.toFixed(2)
                    };
                }
            }
            return null;
        }

        // function to calculate Dale-Chall formula
        const calculateDaleChall = (text) => {
            // word list in text
            const words = text.replace(/[^\w\s'-]/g, '').toLowerCase().split(/\s+/).filter(word => word !== "-" && word.length > 0);

            // daleChallWords: Dale-Chall word list loaded from the assets/js/daleChallWords.js
            const familiarWords = daleChallWords.map(word => word.replace(/[^\w\s'-]/g, '').toLowerCase());

            // count difficult words
            const difficultWordCount = words.filter(word => !familiarWords.includes(word)).length;

            // count words and sentences in text
            const counts = getCounts(text);

            // Dale-Chall formula from https://readabilityformulas.com/learn-about-the-new-dale-chall-readability-formula/
            const score = 64 - (0.95 * 100 * difficultWordCount / counts.wordCount) - (0.69 * counts.wordCount / counts.sentenceCount);

            // get detailed results using the score
            const result = getDaleChallDetails(score);

            return {
                ...result,
                wordCount: counts.wordCount,
                difficultWordCount,
                sentenceCount: counts.sentenceCount
            };
        }

        // function to update Text Readability when click update button
        const updateTextReadability = () => {
            const text = document.getElementById('textInput').value;

            // check if the inputted string is empty or not
            if (text.trim().length === 0) {
                return;
            }

            // get Dale-Chall formula results
            const result = calculateDaleChall(text);

            // display the updated results on the page
            document.getElementById('score').innerText = result.score;
            document.getElementById('readingDifficulty').innerText = result.difficulty;
            document.getElementById('ageRange').innerText = result.age;
            document.getElementById('gradeLevel').innerText = result.grade;
        }

        // function to add hidden input field on the page
        const addHiddenInput = (form, name, value) => {
            const input = document.createElement('input');
            input.type = "hidden";
            input.name = name;
            input.value = value;

            form.appendChild(input);
        }

        // function to add hidden inputs of Dale-Chall formula related parameters before submitting
        const addDaleChallDetailsToForm = () => {
            const text = document.getElementById('textInput').value;
            const result = calculateDaleChall(text);

            const form = document.getElementById('textForm');

            addHiddenInput(form, 'readingDifficulty', result.difficulty);
            addHiddenInput(form, 'gradeLevel', result.grade);
            addHiddenInput(form, 'ageRange', result.age);
            addHiddenInput(form, 'score', result.score);
            addHiddenInput(form, 'wordCount', result.wordCount);
            addHiddenInput(form, 'difficultWordCount', result.difficultWordCount);
            addHiddenInput(form, 'sentenceCount', result.sentenceCount);
        }

        // submit controller
        document.getElementById('textForm').addEventListener('submit', function(event) {
            event.preventDefault();

            addDaleChallDetailsToForm();

            this.submit();
        });
    </script>
</body>

</html>