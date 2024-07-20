<script setup>
import MenuComponent from "../components/menu.vue";
import UserWelcomeComponent from "../components/userWelcome.vue";
import TitleComponent from "../components/title.vue";
import { ref } from "vue";

const formData = ref({
    name: "Ermeson Sampaio de Queiroz",
    age: 17,
    class: "5 série",
    matriculate_date: "2024-07-17",
    needAction: true,
    actionStatus: "pending",
});
</script>

<template>
    <div class="school-register">
        <MenuComponent />
        <UserWelcomeComponent />

        <div class="register-content">
            <TitleComponent title="DADOS REFERENTES AO ALUNO" />
            <form class="student-form">
                <div>
                    <label>
                        Nome do aluno
                        <input
                            readonly
                            id="name"
                            name="name"
                            placeholder="Nome do aluno"
                            type="text"
                            class="input"
                            v-model="formData.name"
                        />
                    </label>

                    <label>
                        Idade do aluno
                        <input
                            readonly
                            id="age"
                            name="age"
                            placeholder="Idade do aluno"
                            type="number"
                            class="input"
                            v-model="formData.age"
                            inputmode="numeric"
                        />
                    </label>

                    <label>
                        Turma atual
                        <input
                            readonly
                            id="class"
                            name="class"
                            placeholder="Turma atual"
                            type="text"
                            class="input"
                            v-model="formData.class"
                        />
                    </label>

                    <label>
                        Matriculado em
                        <input
                            id="class"
                            name="class"
                            placeholder="Matriculado em"
                            type="date"
                            class="input"
                            v-model="formData.matriculate_date"
                        />
                    </label>
                </div>

                <div>
                    <label>
                        Necessário ação de intervenção?
                        <select class="input" v-model="formData.needAction">
                            <option v-bind:value="false">Não</option>
                            <option v-bind:value="true">Sim</option>
                        </select>
                    </label>

                    <div
                        class="radio-container"
                        :style="{
                            color: `var(--${
                                formData.needAction
                                    ? 'black-color'
                                    : 'black-frap-color'
                            })`,
                        }"
                    >
                        <h3>Qual o estado da ação aplicada?</h3>
                        <label for="success"
                            ><input
                                id="success"
                                name="success"
                                type="radio"
                                v-model="formData.actionStatus"
                                value="success"
                            />
                            Eficaz</label
                        >
                        <label for="pending"
                            ><input
                                id="pending"
                                name="pending"
                                type="radio"
                                v-model="formData.actionStatus"
                                value="pending"
                            />
                            Em andamento
                        </label>
                        <label for="fail">
                            <input
                                id="fail"
                                name="fail"
                                type="radio"
                                v-model="formData.actionStatus"
                                value="fail"
                            />
                            Ineficaz
                        </label>
                        <label for="stopped">
                            <input
                                id="stopped"
                                name="stopped"
                                type="radio"
                                v-model="formData.actionStatus"
                                value="stopped"
                            />
                            Interrompida
                        </label>
                    </div>
                </div>

                <div>
                    <img
                        class="student-image"
                        src="https://placehold.co/300x300"
                        alt="Nome do aluno"
                    />
                </div>
            </form>

            <TitleComponent title="OBSERVAÇÕES DO PROFESSOR" />
            <div class="teacher-comments-content">
                <span class="textarea-wrapper">
                    <h3>Observações do Professor</h3>
                    <textarea rows="12"></textarea>
                </span>
            </div>

            <TitleComponent title="MATERIAL DE APOIO" />
            <div class="support-material-content">
                <div class="material-card"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg></div>
                <div class="material-card" style="background-image: url('https://m.media-amazon.com/images/I/51E2055ZGUL._SL1000_.jpg');"></div>
            </div>

            <TitleComponent title="AVALIAÇÕES" />
            <div class="tests-content">
                <div class="test-table-container">
                    <h2>2° Período de avaliações</h2>
                    <table class="test-table">
                        <tr>
                            <th>Leitura</th>
                            <th>Escrita</th>
                            <th>Ações</th>
                        </tr>
                        <tr>
                            <td>Leitor de texto com fluência</td>
                            <td>Ortográfico</td>
                            <td>Hello, World!</td>
                        </tr>
                    </table>
                </div>

                <div class="test-table-container">
                    <h2>1° Período de avaliações</h2>
                    <table class="test-table">
                        <tr>
                            <th>Leitura</th>
                            <th>Escrita</th>
                            <th>Ações</th>
                        </tr>
                        <tr>
                            <td>Leitor de texto com fluência</td>
                            <td>Ortográfico</td>
                            <td>Hello, World!</td>
                        </tr>
                    </table>
                </div>

                <button class="create-test">Adicionar avaliação</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.school-register {
    display: flex;
    height: 100vh;
    width: 100vw;

    & .register-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 8rem 0;
        gap: 2rem;
    }
}

.student-form {
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: 1fr;
    gap: 3rem;
    width: 84%;

    & > div {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
}

.input {
    width: 100%;
    border: 0 solid var(--secondary-color);
    border-bottom-width: 1px;
    background-color: transparent;
    outline: 0;
    font-size: 13pt;
    color: var(--primary-color);
    font-weight: bold;
}

.radio-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.student-image {
    aspect-ratio: 4 / 5;
    object-fit: cover;
    border: 2px solid var(--primary-color);
    border-radius: 10px;
    width: 100%;
}

.textarea-wrapper {
    display: flex;
    flex-direction: column;
    border: 2px solid var(--secondary-color);
    border-radius: 1rem;
    align-items: center;
    cursor: text;
    background-color: white;
    padding: 5px 0;

    & > h3 {
        padding: 10px 0;
    }
}

.teacher-comments-content {
    display: flex;
    flex-direction: column;
    width: 84%;
}

textarea {
    width: 100%;
    background-color: transparent;
    padding: 1rem;
    outline: none;
    border: none;
    resize: none;
    color: var(--primary-color);
    font-weight: 500;
}

.tests-content {
    display: flex;
    flex-direction: column;
    width: 84%;
    position: relative;
    padding-bottom: 5vh;
    gap: 2rem;

    & > .create-test {
        position: absolute;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        border: 2px solid var(--secondary-color);
        text-align: center;
        border-radius: 4rem;
        background-color: transparent;
        color: var(--secondary-color);
        padding: 0.6rem 1.6rem;
        background-color: #fff;
        right: 0;
        font-weight: 700;
    }

    & > .test-table-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 1.8rem;

        & > h2 {
            position: relative;
            transform: translateX(20px);
        }

        & > h2::before {
            content: "";
            position: absolute;
            top: 50%;
            transform: translate(-20px, -50%);
            border-radius: 2px;
            width: 5px;
            height: 90%;
            background-color: var(--black-color);
        }

        & > .test-table::before {
            content: "";
            position: absolute;
            height: 100%;
            width: 10px;
            background-color: var(--secondary-color);
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            transform: translateX(-10px);
        }

        & > .test-table {
            transform: translateX(10px);
            position: relative;
            width: calc(100% - 10px);
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 1rem;

            th {
                font-size: 1.2rem;
                font-weight: 600;
                padding: 10px 1rem;
            }

            td,
            th {
                background-color: rgba(118, 171, 59, 0.1);
            }

            tr th:last-child {
                border-top-right-radius: 1rem;
            }

            tr:nth-child(2) td:last-child {
                border-bottom-right-radius: 1rem;
            }
        }
    }
}

.support-material-content {
    display: flex;
    width: 84%;
    justify-content: flex-start;
    gap: 2rem;

    & > .material-card {
        display: flex;
        align-items: center;
        justify-content: center;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        color: var(--secondary-color);
        aspect-ratio: 5 / 7;
        width: 10vw;
        border-radius: .5rem;
        background-color: #fff;
        border: 2px solid var(--secondary-color);
    }
}

@media screen and (min-width: 1200px) {
    .student-form {
        grid-template-columns: repeat(2, 1.8fr) 1fr;
    }
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>
