const modifyDiv = document.querySelectorAll('.modify-input');


modifyDiv.forEach(div => {
    div.addEventListener('click', function () {
        const input = div.querySelector('input');
        const selectInput = input.closest('#search-form');
        
        if (input) {

            input.focus();
            input.addEventListener('focusout', function(){
                let searchForm = selectInput;
                let formData = new FormData(searchForm);

                fetch('modify-task.php', {
                    method: "POST",
                    body: formData,
                })
                .then((datas) => datas.json())
                .then((datas) => {
                    console.log(datas)
                })
            })
        }
    });
});



