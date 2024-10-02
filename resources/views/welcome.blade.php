<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            color: #333;
        }
        .form-section {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .submissions-list {
            margin-top: 30px;
        }
        .submission-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Submission Form</h1>
    <div id="form-container"></div>
    <h2>Submissions</h2>
    <div class="submissions-list" id="submissions-list"></div>

    <script>
        async function loadForm() {
            const response = await fetch('/api/submissions');
            const submissions = await response.json();
            const formContainer = document.getElementById('form-container');

            submissions.forEach(submission => {
                const formElement = document.createElement('form');
                formElement.classList.add('form-section');
                formElement.innerHTML = `<h2>${submission.name}</h2>`;

                submission.payloads.forEach(payload => {
                    const label = document.createElement('label');
                    label.innerText = payload.label;
                    formElement.appendChild(label);

                    if (payload.type === 'radio_button') {
                        payload.options.forEach(option => {
                            const input = document.createElement('input');
                            input.type = 'radio';
                            input.name = payload.id;
                            input.value = option.id;
                            const optionLabel = document.createElement('span');
                            optionLabel.innerText = option.label;
                            formElement.appendChild(input);
                            formElement.appendChild(optionLabel);
                            formElement.appendChild(document.createElement('br'));
                        });
                    } else if (payload.type === 'text') {
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.name = payload.id;
                        formElement.appendChild(input);
                    } else if (payload.type === 'date') {
                        const input = document.createElement('input');
                        input.type = 'date';
                        input.name = payload.id;
                        formElement.appendChild(input);
                    } else if (payload.type === 'number') {
                        const input = document.createElement('input');
                        input.type = 'number';
                        input.name = payload.id;
                        formElement.appendChild(input);
                    } else if (payload.type === 'long_text') {
                        const textarea = document.createElement('textarea');
                        textarea.name = payload.id;
                        formElement.appendChild(textarea);
                    }
                    formElement.appendChild(document.createElement('br'));
                });

                const submitButton = document.createElement('button');
                submitButton.type = 'submit';
                submitButton.innerText = 'Submit';
                formElement.appendChild(submitButton);

                formElement.addEventListener('submit', async (event) => {
                    event.preventDefault(); // Mencegah pengiriman formulir standar
                    const formData = new FormData(formElement);
                    const data = {
                        name: submission.name,
                        submission_id: submission.id,
                        payloads: Object.fromEntries(formData.entries())
                    };

                    console.log('Submitting data:', data);

                    const result = await fetch('/api/submissions/store', { // Perubahan URL POST
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    });

                    if (result.ok) {
                        alert('Submission successful!');
                        loadSubmissions(); // Memuat ulang daftar setelah pengiriman berhasil
                    } else {
                        alert('Submission failed!');
                    }
                });

                formContainer.appendChild(formElement);
            });
        }

        async function loadSubmissions() {
            const response = await fetch('/api/submissions');
            const submissions = await response.json();
            const submissionsList = document.getElementById('submissions-list');
            submissionsList.innerHTML = '';

            submissions.forEach(submission => {
                const submissionItem = document.createElement('div');
                submissionItem.classList.add('submission-item');
                submissionItem.innerHTML = `\
                    <h3>${submission.name} (ID: ${submission.submission_id})</h3>\
                    <pre>${JSON.stringify(submission.payloads, null, 2)}</pre>\
                `;
                submissionsList.appendChild(submissionItem);
            });
        }

        loadForm();
        loadSubmissions(); // Memuat daftar pengiriman saat halaman dimuat
    </script>
</body>
</html>
