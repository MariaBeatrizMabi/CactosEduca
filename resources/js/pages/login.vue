<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
const acess_cod = ref('');
const password = ref('');
const router = useRouter(); 
import InputComponentPassword from '../components/inputPassword.vue'

const showWrongMessage = ref(false); 

const login = () => {
    axios.post('/login', {
        acess_cod: acess_cod.value,
        password: password.value
    }).then(response => {   
        console.log('Tipo de usuário:', response.data.type);
        if (response.data.type === 'admin') {
            router.push('/dashboard'); 
        }
        else if (response.data.type === 'admin_seduc') {
            console.log("Olá seduc")
        }
        else if (response.data.type === 'teacher') {
            router.push('/dashboard'); 
            console.log("Olá professor")
        }
        else if (response.data.type === 'school') {
            router.push('/dashboard'); 
            console.log("Olá escola")
        } 
    }).catch(error => {
        if (acess_cod.value === '') {
            console.log("Digite um código de acesso")
        }  else if (password.value === '') {
            console.log("Digite uma senha")
        } else {
            showWrongMessage.value = true;
        }
    });
}
</script>

<template>
  <router-view />
      <div class="content">
        <section class="left-section">
            <div class="container">
                <form @submit.prevent="login">
                        <div class="box-content">
                    <div class="gif-abc"></div>

                            <h1>Seja bem-vindo(a)</h1>
                            <p>Veja aqui a evolução de nosso pequenos, e testemunhe cada momento encantador que moldará o futuro de maneira extraordinária.</p>

                            <div class="box-content-input">
                                <label for="acess_cod">Usuário:</label>
                                <div class="icon-email">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path fill="#0d5413" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                                    </svg>
                                    <hr>
                                    <input v-model="acess_cod" placeholder="Digite o seu usuário" type="text" required>                                    </div>

                                <label class="label-passworld" for="password">Senha:</label>
                                <div class="icon-password">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <path fill="#0d5413" d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/></svg>
                                    <hr>
                                        <input v-model="password" placeholder="Digite a sua senha" type="password" required>
                                    </div>
                            </div>
                                <h5 class="text-red" :class="{'wrong-acess': true, 'hidden': !showWrongMessage}">Usuário ou senha incorreto</h5>
                            <button class="button-submit-login" type="submit" >Entrar</button>
                    </div>
                </form>
            </div>
        </section>
        <section class="right-section">
            <div class="container">
                <div class="content">
                        <img class="wave-right-top" src="/public/assets/waveRightTop.svg">
                    <div class="description">
                        <h1>Tenha acesso à análise dos dados de diversos alunos e contribua para a melhoria de seu desempenho.</h1>
                        <img class="image-animate" src="/public/assets/animate.gif" alt="" srcset="">
                        <p>Acesse análises detalhadas do desempenho de alunos e participe ativamente na promoção de melhorias contínuas. Contribua para o progresso educacional com informações valiosas.</p>
                    </div>

                    <div class="wave-left">
                        <img class="wave-left-bottom" src="/public/assets/waveLeftBottom.svg">
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
    .text-red {
        color: red;
    }

    .content {
        width: 100vw;
        height: 100vh;
        display: flex;
        background: white;
        z-index: -1000;

    & .left-section {
        width: 50%;
        z-index: 2000;
        background: url('/assets/BackgroundCactus.png');
        background-size: cover;

        border-radius: 0 6vw 6vw 0;
        border-right: 0.5vh solid var(--primary-color);
        border-top: 0.4vh solid var(--primary-color);
        border-bottom: 0.4vh solid var(--primary-color);

        display: flex;
        align-items: center;

        & .container {
            display: flex;
            flex-direction: column;
            gap: 1vh;
            width: 100%;
        }

        & .gif-abc {
            width: 4.5vw;
            height: 8vh;

            background-color: var(--primary-color);
            background-image: url('/assets/louseGif.gif');
            background-size: 5vh;
            background-repeat: no-repeat;
            background-position: center;

            border-radius: 2vh;
        }

        & .box-content {
            margin: 0 7vw;
            width: 70%;

            color: var(--black-color);

            display: flex;
            flex-direction: column;
            gap: 2vh;

            & .label-passworld {
                margin-top: 1vh;
            }
            
            & h1 {
                font-size: 3vh;
            }

            & p {
                font-size: 1.8vh;
                font-weight: 500;
            }

            & .box-content-input {
                display: flex;
                flex-direction: column;
                color: var(--black-color);

                & label {
                    font-weight: bold;
                    font-size: 1.8vh;
                }

                & input {
                    padding: 1vh;
                    height: 6vh;

                    background-color: var(--grap-color);
                    
                    border-radius: 0 3vw 3vw 0;
                    border: 1px solid var(--primary-color);
                    border-left: 0;
                    
                    outline: 0;
                    width: 100%;
                    font-size: 1rem;
                }
                
                & .icon-password {
                    display: flex;
                    align-items: center;

                    & hr {
                        height: 3vh;
                        margin: 0.3vh 0;
                        color: var(--primary-color);
                    }
                    & svg {
                        padding: 1.4vh 1vw;
                        width: 4vw;
                        height: 6vh;

                        margin: -0.2vw;

                        background-color: var(--grap-color);

                        border: 1px solid var(--primary-color);
                        border-radius: 4vw 0 0 4vw;
                        border-right: 0;
                    }
                }

                & .icon-password:hover, .icon-email:hover  {
                    & svg {
                        border: 1px solid var(--secondary-color);
                        border-radius: 4vw 0 0 4vw;
                        border-right: 0;
                    }

                    & path {
                        fill: var(--secondary-color) !important;
                    }

                    & hr {
                        height: 3vh;
                        margin: 0.3vh 0;
                        color: var(--secondary-color);
                    }

                    & input {
                        border: 1px solid var(--secondary-color);
                        border-left: 0;
                    }
                }

                & .icon-email {
                    display: flex;
                    align-items: center;

                    & hr {
                        height: 3vh;
                        margin: 0.3vh 0;
                        color: var(--primary-color);
                    }

                    & svg {
                        padding: 1.5vh 1.4vw;
                        width: 4vw;
                        height: 6vh;

                        margin: -0.2vw;

                        background-color: var(--grap-color);
                        
                        border: 1px solid var(--primary-color);
                        border-radius: 3vw 0 0 3vw;
                        border-right: 0;                        
                    }
                }
            }
            & .button-submit-login {
                    padding: 1.5vh;
                    
                    border: 0;
                    border-radius: 3vw;
                    
                    color: var(--white-color);
                    
                    font-size: 2.1vh;
                    font-weight: bold;
                    
                    background-color: var(--primary-color);
                    cursor: pointer;
            }

            & .button-submit-login:hover {
                    background-color: var(--secondary-color);
                    transition: 0.3s;
            }
        }
    }

    & .right-section {
        width: 50vw;
        z-index: 1000;

        & .container {
            height: 100%;
            z-index: -1000;
            position: relative;

            & .content {
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;

                & .wave-right-top {
                    top: 0;
                    right: 0;
                    width: 35vh;
                    position: absolute;
                }

                & .description {
                    width: 85%;
                    text-align: center;

                    & h1 {
                        color: var(--primary-color);
                        font-size: 2.4vh;
                    }

                    & img {
                        width: 90%;
                        margin: 2vh 0;
                    }

                    & p {
                        color: var(--black-color);
                        font-size: 1.8vh;
                    }
                }
            
                & .wave-left {
                position: absolute;
                left: 0;
                bottom: 0;
                margin: 0 -30vh;

                    & img {
                            position: absolute;
                            bottom: 0;
                            width: 65vh;
                        }
            }
            }
        }
    }

    @media (max-width: 1910px) {
        & .box-content-input {
            & .icon-email, .icon-password {
                & svg {
                width: 7rem !important;
                }
            }
        }
    }
}

@media (max-width: 1400px) {
        .content {
            display: flex;
            flex-direction: column;
            height: 100% !important;

        & .left-section, .right-section {
            width: 100% !important; 
            height: 100vh !important;
        }

        & .left-section {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;

                border: 0;
                & form {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-direction: column;
                    & .box-content {
                        margin: 0;
                        width: 70%;
                    }
                    & .gif-abc {
                        margin: 0;
                        width: 6rem;
                    }
            }
        }
    }
}

.hidden {
    display: none;
}
</style>