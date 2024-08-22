<script setup>
import Menu from '../components/menu.vue';
import UserWelcome from '../components/userWelcome.vue';
import Breadcrumb from '../components/Breadcrumb';
import Title from "../components/title.vue";
import Modal from "../components/modal.vue";
import SelectComponent from "../components/SelectComponent.vue";
import { useRoute } from "vue-router";
import { api } from '../api'
import { ref, onMounted } from 'vue'


const route = useRoute();

const studentId = route.params.student;
const classId = route.params.class;

const studentData = ref({
    name: '',
    age: null,
    group_id: null,
    enrollment_date: '',
    needAction: false,
    actionStatus: '',
    comments: ''
});

const pollData = ref({
    name: '',
    school_id: null,
    class_id: null,
    active: false,
    average: '',
    year: ''
});

const availableSchools = ref([]);
const availableClasses = ref([]);

async function fetchSchoolsAndClasses() {
    const { data: schools } = await api.get('/schools');
    const { data: classes } = await api.get('/classes');
    availableSchools.value = schools;
    availableClasses.value = classes;
}

async function submitPollCreate() {
    await api.post('/PollCreate', pollData.value);

    pollData.value = {
        name: '',
        school_id: null,
        class_id: null,
        active: false,
        average: '',
        year: ''
    };
}

const classData = ref({
    name: '',
    shift: '',
})

const createExamData = ref({
    reading: '',
    writing: ''
});

const showModal = ref(false);

async function getStudentData() {
    const { data } = await api.get(`/api/students/${route.params.student}`);
    return data;
}

async function getClassData() {
    const { data } = await api.get(`/api/classes/${route.params.class}`);
    return data;
}

onMounted(async () => {
    fetchSchoolsAndClasses();
    studentData.value = await getStudentData();
    classData.value = await getClassData();
});
</script>

<template>
    <div class="main-container">
        <Menu />
        <UserWelcome />

        <div class="sections-container">
            <Breadcrumb.Root style="width: 84%; margin-bottom: -1rem;">
                <Breadcrumb.Content>
                    <Breadcrumb.Item>
                        <svg
                            style="margin-right: 4px"
                            height="16"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 640 512"
                        >
                            <path
                                d="M144 160A80 80 0 1 0 144 0a80 80 0 1 0 0 160zm368 0A80 80 0 1 0 512 0a80 80 0 1 0 0 160zM0 298.7C0 310.4 9.6 320 21.3 320H234.7c.2 0 .4 0 .7 0c-26.6-23.5-43.3-57.8-43.3-96c0-7.6 .7-15 1.9-22.3c-13.6-6.3-28.7-9.7-44.6-9.7H106.7C47.8 192 0 239.8 0 298.7zM320 320c24 0 45.9-8.8 62.7-23.3c2.5-3.7 5.2-7.3 8-10.7c2.7-3.3 5.7-6.1 9-8.3C410 262.3 416 243.9 416 224c0-53-43-96-96-96s-96 43-96 96s43 96 96 96zm65.4 60.2c-10.3-5.9-18.1-16.2-20.8-28.2H261.3C187.7 352 128 411.7 128 485.3c0 14.7 11.9 26.7 26.7 26.7H455.2c-2.1-5.2-3.2-10.9-3.2-16.4v-3c-1.3-.7-2.7-1.5-4-2.3l-2.6 1.5c-16.8 9.7-40.5 8-54.7-9.7c-4.5-5.6-8.6-11.5-12.4-17.6l-.1-.2-.1-.2-2.4-4.1-.1-.2-.1-.2c-3.4-6.2-6.4-12.6-9-19.3c-8.2-21.2 2.2-42.6 19-52.3l2.7-1.5c0-.8 0-1.5 0-2.3s0-1.5 0-2.3l-2.7-1.5zM533.3 192H490.7c-15.9 0-31 3.5-44.6 9.7c1.3 7.2 1.9 14.7 1.9 22.3c0 17.4-3.5 33.9-9.7 49c2.5 .9 4.9 2 7.1 3.3l2.6 1.5c1.3-.8 2.6-1.6 4-2.3v-3c0-19.4 13.3-39.1 35.8-42.6c7.9-1.2 16-1.9 24.2-1.9s16.3 .6 24.2 1.9c22.5 3.5 35.8 23.2 35.8 42.6v3c1.3 .7 2.7 1.5 4 2.3l2.6-1.5c16.8-9.7 40.5-8 54.7 9.7c2.3 2.8 4.5 5.8 6.6 8.7c-2.1-57.1-49-102.7-106.6-102.7zm91.3 163.9c6.3-3.6 9.5-11.1 6.8-18c-2.1-5.5-4.6-10.8-7.4-15.9l-2.3-4c-3.1-5.1-6.5-9.9-10.2-14.5c-4.6-5.7-12.7-6.7-19-3l-2.9 1.7c-9.2 5.3-20.4 4-29.6-1.3s-16.1-14.5-16.1-25.1v-3.4c0-7.3-4.9-13.8-12.1-14.9c-6.5-1-13.1-1.5-19.9-1.5s-13.4 .5-19.9 1.5c-7.2 1.1-12.1 7.6-12.1 14.9v3.4c0 10.6-6.9 19.8-16.1 25.1s-20.4 6.6-29.6 1.3l-2.9-1.7c-6.3-3.6-14.4-2.6-19 3c-3.7 4.6-7.1 9.5-10.2 14.6l-2.3 3.9c-2.8 5.1-5.3 10.4-7.4 15.9c-2.6 6.8 .5 14.3 6.8 17.9l2.9 1.7c9.2 5.3 13.7 15.8 13.7 26.4s-4.5 21.1-13.7 26.4l-3 1.7c-6.3 3.6-9.5 11.1-6.8 17.9c2.1 5.5 4.6 10.7 7.4 15.8l2.4 4.1c3 5.1 6.4 9.9 10.1 14.5c4.6 5.7 12.7 6.7 19 3l2.9-1.7c9.2-5.3 20.4-4 29.6 1.3s16.1 14.5 16.1 25.1v3.4c0 7.3 4.9 13.8 12.1 14.9c6.5 1 13.1 1.5 19.9 1.5s13.4-.5 19.9-1.5c7.2-1.1 12.1-7.6 12.1-14.9v-3.4c0-10.6 6.9-19.8 16.1-25.1s20.4-6.6 29.6-1.3l2.9 1.7c6.3 3.6 14.4 2.6 19-3c3.7-4.6 7.1-9.4 10.1-14.5l2.4-4.2c2.8-5.1 5.3-10.3 7.4-15.8c2.6-6.8-.5-14.3-6.8-17.9l-3-1.7c-9.2-5.3-13.7-15.8-13.7-26.4s4.5-21.1 13.7-26.4l3-1.7zM472 384a40 40 0 1 1 80 0a40 40 0 1 1-80 0z"
                            />
                        </svg>
                        Voltar
                    </Breadcrumb.Item>
                </Breadcrumb.Content>
            </Breadcrumb.Root>

            <div class="header-container">
                <h1 class="header-title">Turmas</h1>
            </div>
            <Button @click="showModal = true">Adicionar Nova Turma</Button>
        </div>

        <TableSchoolComponent :table-header="tableHeader" :table-content="tableContent" :table-actions="tableActions" />

        <div class="footer-container">
            <Footer />
        </div>
    </div>

    <Modal v-model="showModal" title="Adicionar Nova Turma">
        <div class="form-container">
            <div class="form-group">
                <label for="class-name">Nome da Turma:</label>
                <Input id="class-name" v-model="form.className" />
            </div>
            <div class="form-group">
                <label for="order-type">Tipo de Ordenação:</label>
                <SelectComponent v-model="form.orderType">
                    <option value="alphabetical">Alfabético</option>
                    <option value="orthographic">Ortográfico</option>
                </SelectComponent>
            </div>
        </div>
        <div class="modal-actions">
            <Button @click="submitForm">Salvar</Button>
            <Button @click="showModal = false">Cancelar</Button>
        </div>
    </Modal>
</template>

<style scoped>
.main-container {
    display: flex;
    height: 100vh;
    width: 100vw;

    & .sections-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        margin: 5rem 0;
        gap: 2rem;
    }
}

section {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
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
</style>
