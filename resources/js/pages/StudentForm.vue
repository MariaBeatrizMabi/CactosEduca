<script setup>
import MenuComponent from "../components/menu.vue";
import UserWelcomeComponent from "../components/userWelcome.vue";
import TitleComponent from "../components/title.vue";
import { ref } from "vue";

const formData = ref({
    name: 'Ermeson Sampaio de Queiroz',
    age: 17,
    class: '5 série',
    matriculate_date: '2024-07-17',
    needAction: true,
    actionStatus: 'pending'
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
                        :style="{ color: `var(--${formData.needAction ? 'black-color' : 'black-frap-color'})` }"
                    >
                        <h3>Qual o estado da ação aplicada?</h3>
                        <label for="success"><input id="success" name="success" type="radio" v-model="formData.actionStatus" value="success" /> Eficaz</label>
                        <label for="pending"><input id="pending" name="pending" type="radio" v-model="formData.actionStatus" value="pending" /> Em andamento</label>
                        <label for="fail"><input id="fail" name="fail" type="radio" v-model="formData.actionStatus" value="fail" /> Ineficaz</label>
                        <label for="stopped"><input id="stopped" name="stopped" type="radio" v-model="formData.actionStatus" value="stopped" /> Interrompida</label>
                    </div>
                </div>

                <div>
                    <img class="student-image" src="https://placehold.co/300x300?text=foto" alt="Nome do aluno" />
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.school-register {
    display: flex;
    height: 100vh;
    width: 100vw;

    & .register-content {
        padding: 0 10rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 8rem 0;
    }
}

.student-form {
    display: grid;
    grid-template-columns: repeat(2, 1.8fr) 1fr;
    grid-template-rows: 1fr;
    column-gap: 3rem;
    margin-top: 3rem;
    max-width: 84%;

    & > div {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
}

.input {
    width: 100%;
    padding: 1rem;
    border: 0 solid var(--secondary-color);
    border-bottom-width: 2px;
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
    border-radius: 2rem;
    width: 100%;
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
