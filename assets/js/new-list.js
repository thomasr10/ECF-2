const newListInput = document.getElementById('new-list');
let newListForm = document.getElementById('create-list');
const closeListInput = document.getElementById('close-list');
let list = document.querySelectorAll('.display-list');


newListInput.addEventListener('click', function(){
    newListForm.classList.remove('none');
    list.forEach(li => {
        li.classList.add('none');
    });
})

closeListInput.addEventListener('click', function(){
    newListForm.classList.add('none');
    list.forEach(li => {
        li.classList.remove('none');
    })
})