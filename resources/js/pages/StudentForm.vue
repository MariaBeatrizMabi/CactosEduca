<script setup>
import MenuComponent from "../components/menu.vue";
import UserWelcomeComponent from "../components/userWelcome.vue";
import TitleComponent from "../components/title.vue";
import { computed, onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { api } from '../api';

const route = useRoute();

const studentData = ref({
    name: '',
    age: null,
    group_id: null,
    enrollment_date: '',
    needAction: false,
    actionStatus: '',
    comments: ''
});

const formData = ref({
    name: '',
    age: null,
    group_id: null,
    enrollment_date: '',
    needAction: false,
    actionStatus: '',
    comments: ''
});

const availableClassSchool = ref([]);

const hasChangesToUpdate = computed(() =>
    Object.entries(studentData.value)
        .filter(([key]) => key != 'comments')
        .map(([key, value]) => value == formData.value[key])
        .some((value) => !value)
)

const commentsHasChanges = computed(() => studentData.value.comments != formData.value.comments)

async function updateStudent() {
    await api.put(`/api/students/${route.params.student}`, {
        ...formData.value,
        comments: studentData.value.comments
    });
    studentData.value = {
        ...formData.value,
        comments: studentData.value.comments
    };
}

async function updateStudentComments() {
    await api.put(`/api/students/${route.params.student}`, {
        ...studentData.value,
        comments: formData.value.comments
    });

    studentData.value.comments = formData.value.comments;
}

function resetForm() {
    formData.value = studentData.value
}

onMounted(async () => {
    const { data } = await api.get(`/api/students/${route.params.student}`);

    studentData.value = {
        ...data,
        needAction: false,
        actionStatus: '',
    };

    formData.value = studentData.value

    const { data: classSchoolData } = await api.get('/ClassSchool');
    availableClassSchool.value = classSchoolData
});
</script>

<template>
    <div class="school-register">
        <MenuComponent />
        <UserWelcomeComponent />

        <div class="register-content">
            <TitleComponent title="DADOS REFERENTES AO ALUNO" />

            <div class="student-form-wrapper">
                <form class="student-form">
                    <div>
                        <label>
                            Nome do aluno
                            <input
                                id="name"
                                name="name"
                                placeholder="Nome do aluno"
                                type="text"
                                class="input"
                                :value="formData.name"
                                @input="formData = { ...formData, name: $event.target.value }"
                            />
                        </label>

                        <label>
                            Sexo
                            <select
                                id="class"
                                name="class"
                                class="input"
                                :value="formData.gender"
                                @input="formData = { ...formData, gender: $event.target.value }"
                            >
                                <option value="" disabled>Selecione uma opção</option>
                                <option value="male">Masculino</option>
                                <option value="female">Feminino</option>
                            </select>
                        </label>

                        <label>
                            Data de nascimento
                            <input
                                id="class"
                                name="class"
                                placeholder="Data de nascimento"
                                type="date"
                                class="input"
                                :value="formData.date_of_birth"
                                @input="formData = { ...formData, date_of_birth: $event.target.value }"
                            />
                        </label>

                        <label>
                            Matrícula
                            <input
                                id="name"
                                name="name"
                                placeholder="Matrícula"
                                type="text"
                                class="input"
                                :value="formData.enrollment"
                                @input="formData = { ...formData, enrollment: $event.target.value }"
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
                                :value="formData.enrollment_date"
                                @input="formData = { ...formData, enrollment_date: $event.target.value }"
                            />
                        </label>
                    </div>

                    <div>
                        <label>
                            Necessário ação de intervenção?
                            <select class="input" v-model="studentData.needAction">
                                <option v-bind:value="false">Não</option>
                                <option v-bind:value="true">Sim</option>
                            </select>
                        </label>

                        <div
                            class="radio-container"
                            :style="{
                                color: `var(--${
                                    studentData.needAction
                                        ? 'black-color'
                                        : 'black-frap-color'
                                })`,
                            }"
                        >
                            <h3>Qual o estado da ação aplicada?</h3>
                            <label for="success">
                                <input
                                    id="success"
                                    name="success"
                                    type="radio"
                                    v-model="studentData.actionStatus"
                                    value="success"
                                />
                                Eficaz
                            </label>
                            <label for="pending">
                                <input
                                    id="pending"
                                    name="pending"
                                    type="radio"
                                    v-model="studentData.actionStatus"
                                    value="pending"
                                />
                                Em andamento
                            </label>
                            <label for="fail">
                                <input
                                    id="fail"
                                    name="fail"
                                    type="radio"
                                    v-model="studentData.actionStatus"
                                    value="fail"
                                />
                                Ineficaz
                            </label>
                            <label for="stopped">
                                <input
                                    id="stopped"
                                    name="stopped"
                                    type="radio"
                                    v-model="studentData.actionStatus"
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

                <div v-if="hasChangesToUpdate" class="student-form-actions-container">
                    <button class="student-form-action-button" @click="updateStudent">Salvar</button>
                    <button class="student-form-action-button" @click="resetForm">Cancelar</button>
                </div>
            </div>

            <TitleComponent title="OBSERVAÇÕES DO PROFESSOR" />
            <div class="teacher-comments-content">
                <span class="textarea-wrapper">
                    <h3>Observações do Professor</h3>
                    <textarea
                        :value="formData.comments"
                        @input="formData = { ...formData, comments: $event.target.value }"
                        rows="12"
                    ></textarea>
                </span>
            </div>
            <div v-if="commentsHasChanges" class="student-form-actions-container">
                <button class="student-form-action-button" @click="updateStudentComments">Salvar</button>
                <button class="student-form-action-button" @click="formData.comments = studentData.comments">Cancelar</button>
            </div>

            <TitleComponent title="MATERIAL DE APOIO" />
            <div class="support-material-content">
                <div class="material-card">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="40"
                        height="40"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-plus"
                    >
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </div>
                <div
                    class="material-card"
                    style="
                        background-image: url('https://m.media-amazon.com/images/I/51E2055ZGUL._SL1000_.jpg');
                    "
                ></div>
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
    width: 100%;

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
        border-radius: 0.5rem;
        background-color: #fff;
        border: 2px solid var(--secondary-color);
    }
}

.student-form-wrapper {
    display: flex;
    width: 84%;
    flex-direction: column;
    align-items: center;
}

.student-form-actions-container {
    display: flex;
    gap: 1rem;
}

.student-form-action-button {
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
