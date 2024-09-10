<script setup>
import ModalComponentExit from '../components/modalComponentShort.vue';
import { ref } from 'vue';
import axios from 'axios';


const exitModal = ref(false);

function openExitModal() {
    exitModal.value = true;
}

const logout = () => {
    showExitModal();
    axios.post('/logout')
        .then(response => {
            ExitModal
            window.location.href = '/';
        })
        .catch(error => {
            console.error('Erro ao fazer logout:', error);
        });
};

function toggleMenu() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('sidebar-expanded');
}

function closeModalExit() {
    exitModal.value = false;
}

</script>

<template>
   <ModalComponentExit class="exitModal" v-if="exitModal" Titlevalue="Você realmente gostaria de sair do sistema ?">
        <div class="logo-container">
            <img src="/public/assets/logocactus.png" alt="Cactus educa logo" class="logo">
        </div>
        <h1 class="deleted-title">
                A editora cactos agradece sua visita, a qual foi altamente proveitosa para a construção de um futuro brilhante a nossos pequenos, caso já esteja saindo, sinta-se livre para voltar sempre!!! <br>
            </h1>
            <h3>Lembre-se de nos contatar futuramente:</h3>
            <p>
                Para mais informações consulte: <a href="https://editoracactos.com.br/">Editoracactos</a>
            </p>
        <div class="modal-end-deleted">
                <a class="close-modal-deleted" @click="closeModalExit()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--black-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                    </svg>
                    Cancelar
                </a>
                <a ref="link" href="/">
                    <svg fill="red" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
                    </svg>
                    Sair
                </a>
            </div>
    </ModalComponentExit>


    <nav class="sidebar">
        <div class="side-menu">
            <header>
            <div class="image-text">
                <span class="image">
                    <div class="button-menu-expanded" @click="toggleMenu()">
                        <img src="/public/assets/logocactus.png" alt="Cactus educa logo" class="logo">
                        <div>
                            <svg width="10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/>
                            </svg>
                        </div>
                    </div>
                </span>

                <div class="text header-text">
                </div>
            </div>
        </header>

        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <a href="/dashboard">
                    <li class="nav-link">
                            <svg width="25" viewBox="0 0 29 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M28.9924 12.9746C28.9924 13.8887 28.2371 14.6047 27.3811 14.6047H25.7699L25.8051 22.7398C25.8051 22.877 25.7951 23.0141 25.78 23.1512V23.9688C25.78 25.091 24.8787 26 23.7659 26H22.9603C22.9049 26 22.8495 26 22.7941 25.9949C22.7236 26 22.6531 26 22.5827 26H20.9462H19.7378C18.625 26 17.7237 25.091 17.7237 23.9688V22.75V19.5C17.7237 18.6012 17.0037 17.875 16.1125 17.875H12.89C11.9988 17.875 11.2787 18.6012 11.2787 19.5V22.75V23.9688C11.2787 25.091 10.3774 26 9.26468 26H8.05624H6.45003C6.3745 26 6.29897 25.9949 6.22345 25.9898C6.16302 25.9949 6.1026 26 6.04218 26H5.23656C4.12379 26 3.2225 25.091 3.2225 23.9688V18.2812C3.2225 18.2355 3.2225 18.1848 3.22753 18.1391V14.6047H1.61125C0.704921 14.6047 0 13.8938 0 12.9746C0 12.5176 0.151055 12.1113 0.503515 11.7559L13.4136 0.40625C13.7661 0.0507812 14.1689 0 14.5214 0C14.8738 0 15.2766 0.101562 15.5788 0.355469L28.4385 11.7559C28.8413 12.1113 29.0427 12.5176 28.9924 12.9746Z" fill="#0D5413"/>
                            </svg>
                            <span class="text nav-text">Inicio</span>
                    </li>
                </a>
                <a href="/Filters">
                    <li class="nav-link">
                            <svg width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="#0D5413" d="M64 64c0-17.7-14.3-32-32-32S0 46.3 0 64V400c0 44.2 35.8 80 80 80H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H80c-8.8 0-16-7.2-16-16V64zm406.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L320 210.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L240 221.3l57.4 57.4c12.5 12.5 32.8 12.5 45.3 0l128-128z"/>
                            </svg>
                            <span class="text nav-text">Dashboard</span>
                    </li>
                </a>
                <a href="/Management">
                    <li class="nav-link">
                            <svg width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                <path fill="#0D5413" d="M144 160A80 80 0 1 0 144 0a80 80 0 1 0 0 160zm368 0A80 80 0 1 0 512 0a80 80 0 1 0 0 160zM0 298.7C0 310.4 9.6 320 21.3 320H234.7c.2 0 .4 0 .7 0c-26.6-23.5-43.3-57.8-43.3-96c0-7.6 .7-15 1.9-22.3c-13.6-6.3-28.7-9.7-44.6-9.7H106.7C47.8 192 0 239.8 0 298.7zM320 320c24 0 45.9-8.8 62.7-23.3c2.5-3.7 5.2-7.3 8-10.7c2.7-3.3 5.7-6.1 9-8.3C410 262.3 416 243.9 416 224c0-53-43-96-96-96s-96 43-96 96s43 96 96 96zm65.4 60.2c-10.3-5.9-18.1-16.2-20.8-28.2H261.3C187.7 352 128 411.7 128 485.3c0 14.7 11.9 26.7 26.7 26.7H455.2c-2.1-5.2-3.2-10.9-3.2-16.4v-3c-1.3-.7-2.7-1.5-4-2.3l-2.6 1.5c-16.8 9.7-40.5 8-54.7-9.7c-4.5-5.6-8.6-11.5-12.4-17.6l-.1-.2-.1-.2-2.4-4.1-.1-.2-.1-.2c-3.4-6.2-6.4-12.6-9-19.3c-8.2-21.2 2.2-42.6 19-52.3l2.7-1.5c0-.8 0-1.5 0-2.3s0-1.5 0-2.3l-2.7-1.5zM533.3 192H490.7c-15.9 0-31 3.5-44.6 9.7c1.3 7.2 1.9 14.7 1.9 22.3c0 17.4-3.5 33.9-9.7 49c2.5 .9 4.9 2 7.1 3.3l2.6 1.5c1.3-.8 2.6-1.6 4-2.3v-3c0-19.4 13.3-39.1 35.8-42.6c7.9-1.2 16-1.9 24.2-1.9s16.3 .6 24.2 1.9c22.5 3.5 35.8 23.2 35.8 42.6v3c1.3 .7 2.7 1.5 4 2.3l2.6-1.5c16.8-9.7 40.5-8 54.7 9.7c2.3 2.8 4.5 5.8 6.6 8.7c-2.1-57.1-49-102.7-106.6-102.7zm91.3 163.9c6.3-3.6 9.5-11.1 6.8-18c-2.1-5.5-4.6-10.8-7.4-15.9l-2.3-4c-3.1-5.1-6.5-9.9-10.2-14.5c-4.6-5.7-12.7-6.7-19-3l-2.9 1.7c-9.2 5.3-20.4 4-29.6-1.3s-16.1-14.5-16.1-25.1v-3.4c0-7.3-4.9-13.8-12.1-14.9c-6.5-1-13.1-1.5-19.9-1.5s-13.4 .5-19.9 1.5c-7.2 1.1-12.1 7.6-12.1 14.9v3.4c0 10.6-6.9 19.8-16.1 25.1s-20.4 6.6-29.6 1.3l-2.9-1.7c-6.3-3.6-14.4-2.6-19 3c-3.7 4.6-7.1 9.5-10.2 14.6l-2.3 3.9c-2.8 5.1-5.3 10.4-7.4 15.9c-2.6 6.8 .5 14.3 6.8 17.9l2.9 1.7c9.2 5.3 13.7 15.8 13.7 26.4s-4.5 21.1-13.7 26.4l-3 1.7c-6.3 3.6-9.5 11.1-6.8 17.9c2.1 5.5 4.6 10.7 7.4 15.8l2.4 4.1c3 5.1 6.4 9.9 10.1 14.5c4.6 5.7 12.7 6.7 19 3l2.9-1.7c9.2-5.3 20.4-4 29.6 1.3s16.1 14.5 16.1 25.1v3.4c0 7.3 4.9 13.8 12.1 14.9c6.5 1 13.1 1.5 19.9 1.5s13.4-.5 19.9-1.5c7.2-1.1 12.1-7.6 12.1-14.9v-3.4c0-10.6 6.9-19.8 16.1-25.1s20.4-6.6 29.6-1.3l2.9 1.7c6.3 3.6 14.4 2.6 19-3c3.7-4.6 7.1-9.4 10.1-14.5l2.4-4.2c2.8-5.1 5.3-10.3 7.4-15.8c2.6-6.8-.5-14.3-6.8-17.9l-3-1.7c-9.2-5.3-13.7-15.8-13.7-26.4s4.5-21.1 13.7-26.4l3-1.7zM472 384a40 40 0 1 1 80 0 40 40 0 1 1 -80 0z"/>
                            </svg>
                            <span class="text nav-text">Escolas</span>
                    </li>
                </a>
                </ul>
            </div>
        </div>
        <div class="menu-section2">
            <ul class="menu-links">
                <a href="/ContactUs">
                <li class="nav-link">
                    <svg width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="#0D5413" d="M280 0C408.1 0 512 103.9 512 232c0 13.3-10.7 24-24 24s-24-10.7-24-24c0-101.6-82.4-184-184-184c-13.3 0-24-10.7-24-24s10.7-24 24-24zm8 192a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm-32-72c0-13.3 10.7-24 24-24c75.1 0 136 60.9 136 136c0 13.3-10.7 24-24 24s-24-10.7-24-24c0-48.6-39.4-88-88-88c-13.3 0-24-10.7-24-24zM117.5 1.4c19.4-5.3 39.7 4.6 47.4 23.2l40 96c6.8 16.3 2.1 35.2-11.6 46.3L144 207.3c33.3 70.4 90.3 127.4 160.7 160.7L345 318.7c11.2-13.7 30-18.4 46.3-11.6l96 40c18.6 7.7 28.5 28 23.2 47.4l-24 88C481.8 499.9 466 512 448 512C200.6 512 0 311.4 0 64C0 46 12.1 30.2 29.5 25.4l88-24z"/>
                        </svg>
                        <span class="text nav-text">Contate-nos</span>
                </li>
            </a>
            <a style="cursor: pointer;" @click="openExitModal()">
                <li class="nav-link">
                        <svg width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="#0D5413" d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
                        </svg>
                        <span class="text nav-text">Sair</span>
                </li>
            </a>
        </ul>
        </div>
        </div>
    </nav>
</template>

<style>
a {
    text-decoration: none;
}

.school-register{
    display: flex;
    height: 100vh;
    width: 100%;

    & .register-content {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 5rem 0;
        height: 100vh;
    }
}

.sidebar {
    top: 0;
    left: 0;
    width: 170px;
    transition: 0.3s;
    & .text {
    display: none;
    transition: 0.3s;
    }
}

.side-menu{
  top: 0;
  display: flex;
  flex-direction: column;
  align-content: baseline;
  height: 100%;
  justify-content: space-evenly;
  position: fixed;
  background-color: var(--grap-color);
  border-right: 2px solid var(--secondary-color);
}

.sidebar-expanded {
    width: 280px;
    transition: 0.2s;

    & .button-menu-expanded {
        & div {
        transform: translate(165px);
        }
    }

    & .text {
    display: flex;
    transition: 0.3s;
    }

    & .nav-link{
        height: 70px;
        border-radius: 1rem;
    }
}

nav{
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    align-items: center;
    transition: 0.3s;
}

header {
    display: flex;
    justify-content: center;
}

.button-menu-expanded {
    display: flex;
    align-items: center;
    & div {
        border: 2px solid var(--secondary-color);
        padding: 0.8rem 1rem;
        position: absolute;
        transition: 0.2s;
        transform: translate(125px);
        border-radius: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: var(--grap-color);
        cursor: pointer;
        fill: var(--primary-color);
    }
}

.logo{
    width: 10rem;
}

.sidebar li {
    height: 70px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
}

ul {
    display: flex;
    flex-direction: column;
    gap: 3rem;
}

.menu {
    display: flex;
    justify-content: center;
}

.menu-section2 {
    display: flex;
    justify-content: center;
}

.nav-link {
    gap: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: var(--primary-color);
    font-size: 13pt;
    font-weight: 500;
    padding: 0 2rem;
}

.nav-text {
    transition: 0.3s;
    width: 120px;
}

.nav-link:hover{
    transition: 0.3s;
    background-color: var(--primary-color);
        color: var(--grap-color);
        & svg > path {
            fill: var(--grap-color) !important;
        }
}

@media (max-width: 900px) {
    .sidebar{
        display: none;
    }

    .nav-link {
        padding: 0;
        & a {
            width: 100%;
            gap: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--primary-color);
            font-size: 13pt;
            font-weight: 500;
            padding: 0 2rem;
        }
    }
    .nav-link:hover{
    transition: 0.3s;
    background-color: var(--primary-color);
        & a {
            color: var(--grap-color);
        }
            & svg > path {
            fill: var(--grap-color) !important;
        }
        }
}

.exitModal{
    display: flex;
    z-index: 9999;

    & .logo-container {
        width: 90%;
    }

    & .deleted-title{
        color: var(--text-color);
    }

    & h3 {
        width: 90%;
        font-size: 18px;
        color: #008BD0;
    }

    & p {
            width: 90%;
            margin: 1rem 0;
            font-size: 15px;
            & a{
                text-decoration: none;
                font-weight: 600;
            }
    }
}

</style>
