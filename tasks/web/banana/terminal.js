const input = document.getElementById('cmd');
const output = document.getElementById('output');
const sendBtn = document.getElementById('send');


function typeOutput(text, callback) {
let i = 0;
const interval = setInterval(() => {
if (i < text.length) {
output.textContent += text[i];
i++;
output.scrollTop = output.scrollHeight;
} else {
clearInterval(interval);
if (callback) callback();
}
}, 20); // скорость печати
}


async function runCommand() {
const cmd = input.value.trim();
if (!cmd) return;
input.value = '';


// Добавляем строку команды сразу
output.textContent += '\nbanana@server:$ ' + cmd + '\n';


try {
const res = await fetch('run.php', {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({ cmd })
});


const data = await res.json();


// Выводим ответ с анимацией печати
typeOutput(data.out + '\nbanana@server:$ ');
} catch (e) {
typeOutput('Error connecting to server\nbanana@server:$ ');
}
}


input.addEventListener('keydown', e => {
if (e.key === 'Enter') runCommand();
});
sendBtn.addEventListener('click', runCommand);