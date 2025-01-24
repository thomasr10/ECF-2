const addTask = document.getElementById('create-task');
const addTaskInput = document.getElementById('new-task');
const closeTaskInput = document.getElementById('close-task');

const addBee = document.getElementById('add-bee');
const addBeeInput = document.getElementById('new-bee');
const closeBeeInput = document.getElementById('close-bee');


addTaskInput.addEventListener('click', function(){
    addTask.classList.remove('none');
})

closeTaskInput.addEventListener('click', function(){
    addTask.classList.add('none');
})

addBeeInput.addEventListener('click', function(){
    addBee.classList.remove('none');
})

closeBeeInput.addEventListener('click', function(){
    addBee.classList.add('none');
})