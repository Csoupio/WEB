const menuButton = document.getElementById('loginmenu');
const dropdownMenu = document.getElementById('dropdownMenu');

// Écoute le clic sur le bouton uniquement si les éléments existent
if (menuButton && dropdownMenu) {
    menuButton.addEventListener('click', () => {
        dropdownMenu.classList.toggle('show');
    });
}

const ResetPasswordLink = document.getElementById('resetPswd');
const zonePswd = document.getElementById('NewPswd');
const cacheLogin = document.getElementById('login');


    


//gestion du formulaire de création de ticket


function checkTitle() {
    const title = document.getElementById('title');
    const errorTitle = document.getElementById('title_error');
    if (title.value == ""){
        errorTitle.classList.remove('titanic');
        return 1;
    }
    else {
        errorTitle.classList.add('titanic');
        return 0;
    }
}


function checkDesc() {
    const description = document.getElementById('description');
    const errorDesc = document.getElementById('description_error');
    if (description.value == ""){
        errorDesc.classList.remove('titanic');
        return 1;
    }
    else {
        errorDesc.classList.add('titanic');
        return 0;
    }
}

function checkProject() {
    const project = document.getElementById('project');
    const errorProject = document.getElementById('project_error');
    if (project.value == ""){
        errorProject.classList.remove('titanic');
        return 1;
    } else {
        errorProject.classList.add('titanic');
        return 0;
    }
}

function checkPriority() {
    const priority = document.getElementById('priority');
    const errorPriority = document.getElementById('priority_error');
    if (priority.value == ""){
        errorPriority.classList.remove('titanic');
        return 1;
    } else {
        errorPriority.classList.add('titanic');
        return 0;
    }
}

function checkType() {
    const type = document.getElementById('type');
    const errorType = document.getElementById('type_error');
    if (type.value == ""){
        errorType.classList.remove('titanic');
        return 1;
    } else {
        errorType.classList.add('titanic');
        return 0;
    }
}

function checkTime() {
    const time = document.getElementById('estimated_time');
    const errorTime = document.getElementById('estimated_time_error');
    if (time.value === "" || parseFloat(time.value) <= 0){
        errorTime.classList.remove('titanic');
        return 1;
    } else {
        errorTime.classList.add('titanic');
        return 0;
    }
}

const formTicket = document.getElementById('ticketForm');
const toastTicket = document.getElementById('toastTicket');

if(formTicket) {
    formTicket.addEventListener('submit', function(event){
        event.preventDefault();

        let nb_errors = 0;
        nb_errors += checkTitle() + checkDesc() + checkProject() + checkPriority() + checkType() + checkTime();
        console.log("Nombre d'erreurs : " + nb_errors);

        if(nb_errors == 0){
            formTicket.submit();
        }
    });
}






//Création d'un compte

function checkFirstname() {
    const firstname = document.getElementById('firstName');
    const firstname_error = document.getElementById('firstName_error');
    if(firstname.value =="") {
        firstname_error.classList.remove('titanic');
        return 1;
    } else{
        firstname_error.classList.add('titanic');
        return 0;
    }
}


function checkname(){
    const lastname = document.getElementById('lastName');
    const lastname_error = document.getElementById('lastName_error');
    if(lastname.value == "") {
        lastname_error.classList.remove('titanic');
        return 1;
    } else {
        lastname_error.classList.add('titanic');
        return 0;
    }
}

function email_create(){
    const email = document.getElementById('email');
    const email_error = document.getElementById('email-creator_error');
    if(email.value == "") {
        email_error.classList.remove('titanic');
        return 1;
    } else {
        email_error.classList.add('titanic');
        return 0;
    }
}

function password_create(){
    const passworD = document.getElementById('password');
    const passwor_error = document.getElementById('password_error');
    if(passworD.value == "") {
        passwor_error.classList.remove('titanic');
        return 1;
    } else {
        passwor_error.classList.add('titanic');
        return 0;
    }
}

const formAccountCreate = document.getElementById('accountCreate');

if(formAccountCreate){
    formAccountCreate.addEventListener('submit', function(event){
        event.preventDefault();

        let nb_errors = 0;
        nb_errors = checkFirstname() + checkname() + email_create() + password_create();
        console.log("Nb d'erreurs : " + nb_errors);
        if(nb_errors == 0) {
            const a = document.getElementById('firstName');
            const b = document.getElementById('lastName');
            const c = document.getElementById('email');
            const d = document.getElementById('password');

            const row = `
             <tr>
                    <td>${a.value}</td>
                    <td>${b.value}</td>
                    <td>${c.value}</td>
                    <td>${d.value}</td>

                </tr>
            `;
            console.log(row);
            a.value = '';
            b.value = '';
            c.value = '';
            d.value = '';

            window.location.href = 'clients.html';
        } 
    });
}

//Gestion du formulaire de login
function checkLogin() {
    const login = document.getElementById('login-textzone');
    const login_error = document.getElementById('login_error');
    if(login.value == "") {
        login_error.classList.remove('titanic');
        return 1;
    } else {
        login_error.classList.add('titanic');
        return 0;
    }
}

function checkPassword() {
    const password = document.getElementById('password');
    const password_error = document.getElementById('password_error');
    if(password.value == "") {
        password_error.classList.remove('titanic');
        return 1;
    } else {
        password_error.classList.add('titanic');
        return 0;
    }
}

function checkNewPassword() {
    const newPassword = document.getElementById('nouveauPswd');
    const newPassword_error = document.getElementById('newPswd_error');
    if(newPassword.value == "") {
        newPassword_error.classList.remove('titanic');
        return 1;
    } else {
        newPassword_error.classList.add('titanic');
        return 0;
    }
}

function checkNewConfirmPassword() {
    const password = document.getElementById('confirmPswd');
    const password_error = document.getElementById('confirmPswd_error');
    if(password.value == "") {
        password_error.classList.remove('titanic');
        return 1;
    } else {
        password_error.classList.add('titanic');
        return 0;
    }
}

if (ResetPasswordLink ) {
    ResetPasswordLink.addEventListener('click', () => {
        if (zonePswd) zonePswd.classList.toggle('show');
        if (cacheLogin) cacheLogin.classList.add('titanic');
    });
}

const formLogin = document.getElementById('accountLogin');
if(formLogin) {
    formLogin.addEventListener('submit', function(event){
        event.preventDefault();

        let nb_errors = 0;
        if(zonePswd && zonePswd.classList.contains('show')){
            nb_errors += checkNewPassword() + checkNewConfirmPassword();
        }else{
            nb_errors = checkLogin() + checkPassword();
        }
        console.log("Nb d'erreurs : " + nb_errors);
        if(nb_errors == 0) {
            const a = document.getElementById('login');
            let b;
            if(zonePswd && zonePswd.classList.contains('show')){
                b = document.getElementById('nouveauPswd');
            }else {
                b = document.getElementById('password');
            }
                
            const row = `
             <tr>
                    <td>${a.value}</td>
                    <td>${b.value}</td>

                </tr>
            `;
            console.log(row);
            
            console.log(a.value, b.value);
            a.value = '';
            b.value = '';
            
        } 
    });
} 


//Espace administrateur

function checkUserName() {
    const UserName = document.getElementById('adminUserPrenom');
    const UserName_error = document.getElementById('adminUserPrenomError');
    if(UserName.value == "") {
        UserName_error.classList.remove('titanic');
        return 1;
    } else {
        UserName_error.classList.add('titanic');
        return 0;
    }
}

function checkUserEmail() {
    const email = document.getElementById('adminUserEmail');
    const email_error = document.getElementById('adminUserEmailError');
    if(email.value == "") {
        email_error.classList.remove('titanic');
        return 1;
    } else {
        email_error.classList.add('titanic');
        return 0;
    }
}


const formAdminUser = document.getElementById('formAdminUser');
if(formAdminUser) {
    formAdminUser.addEventListener('submit', function(event){
        event.preventDefault();
        let nb_errors = 0;
        nb_errors = checkUserName() + checkUserEmail();
        console.log("Nb d'erreurs : " + nb_errors);
        if(nb_errors == 0) {
            const a = document.getElementById('adminUserPrenom');
            const b = document.getElementById('adminUserEmail');
            const c = document.getElementById('adminUserRole');

            const t = document.getElementById("adminUserTable");
            const row = `
             <tr class="Tickets-ligne">
                    <td>1</td>
                    <td>${a.value}</td>
                    <td>${b.value}</td> 
                    <td>${c.value}</td>
            </tr>
            `;
            console.log(row);
            t.insertAdjacentHTML('beforeend', row);

            formAdminUser.submit();
            
            a.value = '';
            b.value = '';

            
        } 
    });

}

function checkClientName() {
    const UserName = document.getElementById('adminClientName');
    const UserName_error = document.getElementById('adminClientNameError');
    if(UserName.value == "") {
        UserName_error.classList.remove('titanic');
        return 1;
    } else {
        UserName_error.classList.add('titanic');
        return 0;
    }
}

function checkClientEmail() {
    const email = document.getElementById('adminClientEmail');
    const email_error = document.getElementById('adminClientEmailError');
    if(email.value == "") {
        email_error.classList.remove('titanic');
        return 1;
    } else {
        email_error.classList.add('titanic');
        return 0;
    }
}


const formAdminClient = document.getElementById('formAdminClient');
if(formAdminClient) {
    formAdminClient.addEventListener('submit', function(event){
        event.preventDefault();
        let nb_errors = 0;
        nb_errors = checkClientName() + checkClientEmail();
        console.log("Nb d'erreurs : " + nb_errors);
        if(nb_errors == 0) {
            const a = document.getElementById('adminClientName');
            const b = document.getElementById('adminClientEmail');
            const t = document.getElementById("adminClientTable");

            const row = `
             <tr class="client-row">
                    <td>${a.value}</td>
                    <td>${b.value}</td> 

                </tr>
            `;
            
            t.insertAdjacentHTML('beforeend', row);
            console.log(row);
            

            formAdminClient.submit();
            a.value = '';
            b.value = '';
        } 
    });

}

function checkProjetName() {
    const UserName = document.getElementById('adminProjectName');
    const UserName_error = document.getElementById('adminProjectNameError');
    if(UserName.value == "") {
        UserName_error.classList.remove('titanic');
        return 1;
    } else {
        UserName_error.classList.add('titanic');
        return 0;
    }
}

function checkProjetDescription() {
    const email = document.getElementById('adminProjectDescription');
    const email_error = document.getElementById('adminProjectDescriptionError');
    if(email.value == "") {
        email_error.classList.remove('titanic');
        return 1;
    } else {
        email_error.classList.add('titanic');
        return 0;
    }
}


const formAdminProjet = document.getElementById('formAdminProject');
if(formAdminProjet) {
    formAdminProjet.addEventListener('submit', function(event){
        event.preventDefault();
        let nb_errors = 0;
        nb_errors = checkProjetName() + checkProjetDescription();
        console.log("Nb d'erreurs : " + nb_errors);
        if(nb_errors == 0) {
            const a = document.getElementById('adminProjectName');
            const b = document.getElementById('ClientSelect');
            const c = document.getElementById('adminProjectDescription');
            const row = `
             <tr>
                    <td>${a.value}</td>
                    <td>${b.value}</td>
                    <td>${c.value}</td> 

                </tr>
            `;
            console.log(row);
            

            formAdminProjet.submit();

            a.value = '';
            b.value = '';
        } 
    });

}


//Gestion des filtres de la page clients

const filtres = document.querySelectorAll(".filter-btn");

for (let i= 0; i < filtres.length; i++) {
    filtres[i].addEventListener("click", function(event) {
        event.preventDefault();
        // Texte du bouton
        console.log(filtres[i].innerText);

        const trs = document.querySelectorAll('.Tickets-table tbody tr');

        // Je veux parcourir mon tableau
        for (let j=0; j < trs.length ; j++) {
            console.log(trs[j]);
            const genre = trs[j].querySelector(".status");
            // texte de la case dans le tableau

            // Je veux comparer mon texte du bouton, avec celui de la case du tableau
            // Si le texte est différent, on cache la ligne
            if(filtresStatus[i].innerText.toLowerCase() == "tous") {
                trs[j].classList.remove('titanic');
            } else
            if (filtres[i].innerText.toLowerCase() != genre.innerText.toLowerCase()) {
                // On cache toute la ligne (= le tr)
                trs[j].classList.add('titanic');
            } else {
                trs[j].classList.remove('titanic');
            }

        }

    });

}

const filtresStatus = document.querySelectorAll(".filter-btn-Statut");

for (let i= 0; i < filtresStatus.length; i++) {
    filtresStatus[i].addEventListener("click", function(event) {
        event.preventDefault();
        // Texte du bouton
        console.log(filtresStatus[i].innerText);

        const trs = document.querySelectorAll('.Tickets-table tbody tr');

        // Je veux parcourir mon tableau
        for (let j=0; j < trs.length ; j++) {
            console.log(trs[j]);
            const genre = trs[j].querySelector(".priority");
            // texte de la case dans le tableau
            console.log(genre.innerText);

            // Je veux comparer mon texte du bouton, avec celui de la case du tableau
            // Si le texte est différent, on cache la ligne
            if(filtresStatus[i].innerText.toLowerCase() == "tous") {
                trs[j].classList.remove('titanic');
            } else
            if (filtresStatus[i].innerText.toLowerCase() != genre.innerText.toLowerCase()) {
                // On cache toute la ligne (= le tr)
                trs[j].classList.add('titanic');
            } else {
                trs[j].classList.remove('titanic');
            }

        }

    });

}