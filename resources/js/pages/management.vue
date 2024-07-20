<script setup>
import { ref, onMounted, defineEmits } from 'vue';
import axios from 'axios';
import MenuComponent from '../components/menu.vue';
import TitleComponent from '../components/title.vue';
import userWelcomeComponent from '../components/userWelcome.vue';
import TableComponent from '../components/table.vue';
import tableComponentComponent from '../components/tableComponent.vue';

import ModalComponent from '../components/modal.vue'
import InputComponent from '../components/input.vue'
import InputComponentPassword from '../components/inputPassword.vue'
import LoadingComponent from '../components/loading.vue'
import ModalComponentDeleted from '../components/modalComponentShort.vue';
import SelectComponent from '../components/select.vue'

const emit = defineEmits(['viewDetails', 'updateAction', 'deletedAction']);

const userType = ref('');
const userID = ref('');
const showModalCreation = ref(false);
const ShowModalClassCreation = ref(false);
const showModalStudentCreation = ref(false);
const isLoading = ref(false);

const showModalData = ref();
const showModalClassData = ref();
const showModalStudentData = ref();
const updateModalTeacherData = ref();
const updateModalClassData = ref();
const idToUpdate = ref(null);
const deletedModal = ref();
const deletedClassDataModal = ref()
const idToDeleted = ref(null);

let formDataTeachersPreview = ref({
    id: '',
    name: '',
    acess_cod: '',
    password: '',
})

const formDataTeacherAdd = ref({
    name: '',
    school_id: userID.value,
    group_id: '',
    acess_cod: '',
    password: '',
    type: 'teacher'
})

const formDataStudentAdd = ref({
    name: '',
    group_id: '',
    school_id: userID.value, 
})

let formDataClassPreview = ref({
    id: '',
    name: '',
    acess_cod: '',
    password: '',
})

let formDataStudentPreview = ref({
    name: '',
    group_id: '',
    school_id: userID.value,    
})

const formDataClassAdd = ref({
    name: '',
    school_id: userID.value,
    teacher_id: '',
})

let formDataVisualize = ref({
    id: '',
    name: '',
    acess_cod: '',
    password: '',
})

let formDataClassVisualize = ref({
    name: '',
    school_id: userID.value,
    teacher_id: '',
})

let formDataStudentVisualize = ref({
    name: '',
    school_id: userID.value,
    teacher_id: '',
})

let formDataUpdate = ref({
    id: '',
    name: '',
    acess_cod: '',
    password: '',
})

let formDataClassUpdated = ref({
    name: '',
    school_id: userID.value,
    teacher_id: '',
})

let formDataDeleted = ref({
    name: '',
})

let formDataClassDeleted = ref({
    name: '',
})

const OpenModalTeachersCreation = () => {
    showModalCreation.value = true;
};

const OpenModalClassCreation = () => {
    ShowModalClassCreation.value = true;
};

const OpenModalStudentCreation = () => {
    showModalStudentCreation.value = true;
};

function resetForm() {
    formDataTeacherAdd.value = {
        name: '',
        school_id: userID.value,
        group_id: '',
        acess_cod: '',
        password: '',
        type: 'teacher'
    };
}

function resetFormClass() {
    formDataTeacherAdd.value = {
        name: '',
        school_id: userID.value,
        teacher_id: '',
    };
}

const getUserType = () => {
    axios.get('/loginUser').then(response => {
        userType.value = response.data.type;
        userID.value = response.data.id;

         formDataTeacherAdd.value = {
            name: '',
            school_id: userID.value,
            group_id: '',
            acess_cod: '',
            password: '',
            type: 'teacher'
        };

        formDataClassAdd.value = {
            name: '',
            school_id: userID.value,
            teacher_id: '',
        };

        formDataStudentAdd.value = {
            name: '',
            school_id: userID.value,
            group_id: '',
        }

    }).catch(error => {
        console.error("ERROR", error);
    });
}

async function getTableTeacherData() {
    try {
        const response = await axios('/Teachers');

        formDataTeachersPreview.value = response.data.map(take => ({
            id: take.id,
            name: take.name,
            acess_cod: take.user.acess_cod,
        }));

    } catch (error) {
        console.error(error);
    }
}

async function getTableClassData() {
    try {
        const response = await axios('/ClassSchool');
        formDataClassPreview.value = response.data.map(take => ({
            id: take.id,
            name: take.name,
            teacher_name: take.teacher ? take.teacher.name : '',
        }));

    } catch (error) {
        console.error(error);
    }
}

async function getTableStudentData() {
    try {
        const response = await axios('/StudentsData');

        formDataStudentPreview.value = response.data.map(take => ({
            id: take.id,
            name: take.name,
            group_id: take.class_data ? take.class_data.name : '',
        }));


    } catch (error) {
        console.error(error);
    }
}

async function submitForm() {
    try {
        isLoading.value = true;
        await axios.post('/TeacherCreate', formDataTeacherAdd.value);

        getTableTeacherData()

        showModalCreation.value = false;

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error)
        isLoading.value = false;
    }
}

async function submitStudentForm() {
    try {
        isLoading.value = true;
        await axios.post('/StudentCreate', formDataStudentAdd.value);

        getTableStudentData()

        showModalStudentCreation.value = false;

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error)
        isLoading.value = false;
    }
}

async function submitFormClass() {
    try {
        isLoading.value = true;
        await axios.post('/ClassSchoolCreate', formDataClassAdd.value);

        getTableClassData();

        ShowModalClassCreation.value = false;

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error)
        isLoading.value = false;
    }
}

async function ShowSchoolTeachersData(id) {
    showModalData.value = true;

    try {
        const { data: teacher } = await axios.get(`/api/teachers/${id}`);

        if (teacher) {
            formDataVisualize.value = {
                id: teacher.id,
                name: teacher.name,
                acess_cod: teacher.user.acess_cod,
                type: 'teacher'
            };
        } else {
            console.error(`Não foi possível encontrar o professor com o ID ${id}`);
        }
    } catch (error) {
        console.error(error);
    }
}

async function ShowSchoolClassData(id) {
    showModalClassData.value = true;
    try {
        const response = await axios.get(`/ClassSchool`);

        const classData = response.data.find(classData => classData.id === id);
        if (classData) {
            formDataClassVisualize.value = {
                name: classData.name,
                teacher_id: classData.teacher_id,
            };
        } else {
            console.error(`Não foi possível encontrar o professor com o ID ${id}`);
        }
    } catch (error) {
        console.error(error);
    }
}

async function ShowStudentData(id) {
    showModalStudentData.value = true;
    try {
        const response = await axios.get(`/StudentsData`);
        const student = response.data.find(student => student.id === id);

        if (student) {
            formDataStudentVisualize.value = {
                name: student.name,
                group_id: student.class_data ? student.class_data.id : '',
            };

        } else {
            console.error(`Não foi possível encontrar o professor com o ID ${id}`);
        }
    } catch (error) {
        console.error(error);    }
}

async function UpdateSchoolTeachersData(id) {
    updateModalTeacherData.value = true;

    try {
        const { data: teacher } = await axios.get(`/api/teachers/${id}`);

        idToUpdate.value = id;

        if (teacher) {
            formDataUpdate.value = {
                ...formDataUpdate,
                id: teacher.id,
                name: teacher.name,
                acess_cod: teacher.user.acess_cod
            };
        } else {
            console.error(`Não foi possível encontrar o professor com o ID ${id}`);
        }
    } catch (error) {
        console.error(error);
    }
}

async function UpdateSchoolClassData(id) {
    updateModalClassData.value = true;
    try {
        const response = await axios.get(`/ClassSchool/${id}`);

        const classData = response.data.find(classData => classData.id === id);

        idToUpdate.value = id;

        if (classData) {
            formDataClassUpdated.value = {
                name: classData.name,
                teacher_id: classData.teacher_id,
            };
        } else {
            console.error(`Não foi possível encontrar o professor com o ID ${id}`);
        }

    } catch (error) {
        console.error(error);
    }
}

async function updateDataForm() {
    try {
        await axios.put(`/api/teachers/${idToUpdate.value}`, formDataUpdate.value);

        updateModalTeacherData.value = false;
        isLoading.value = true;

        getTableTeacherData();

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
    }
}

async function updateClassDataForm() {
    const id = idToUpdate.value;
    try {
        const formData = {
            name: formDataClassUpdated.value.name,
            school_id: userID.value,
            teacher_id: formDataClassUpdated.value.teacher_id
        };

        const response = await axios.put(`/ClassSchoolUpdate/${id}`, formData); 
        updateModalClassData.value = false;
        isLoading.value = true;

        getTableClassData();

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
    }
}

async function deletedModalTeachersShow(id) {
    deletedModal.value = true;
    resetForm();
    try {
        const response = await axios.get(`/Teachers/${id}`);

        const teachers = response.data.find(classData => classData.id === id);
        idToDeleted.value = id;

        formDataDeleted.value = {
            name: teachers.name,
        };
    } catch (error) {
        console.error('Erro ao buscar dados do professor:', error);
    }
}

async function deletedTeachers() {
    const id = idToDeleted.value;

    try {
        await axios.delete(`/api/teachers/${id}`);
        deletedModal.value = false;
        isLoading.value = true;

        getTableTeacherData()
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
        console.error(error)
    }
}

async function deletedModalClassShow(id) {
    deletedClassDataModal.value = true;
    resetForm();
    try {
        const response = await axios.get(`/ClassSchool/${id}`);

        const ClassData = response.data.find(classData => classData.id === id);
        idToDeleted.value = id;

        formDataClassDeleted.value = {
            name: ClassData.name,
        };
    } catch (error) {
        console.error('Erro ao buscar dados do professor:', error);
    }
}

async function deleteClassData() {
    const id = idToDeleted.value;

    try {
        await axios.delete(`/ClassSchoolDelete/${id}`);
        deletedClassDataModal.value = false;
        isLoading.value = true;

        getTableClassData()
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
        console.error(error)
    }
}

function closeModalDeleted() {
    deletedModal.value = false;
    deletedClassDataModal.value = false;
    resetForm();
}

function closeModalAdd() {
    showModalCreation.value = false;
    ShowModalClassCreation.value = false;
    resetForm();
}

function closeModal() {
    showModalData.value = false;
    showModalClassData.value = false;
    showModalStudentData.value = false;
    resetForm();
}

function closeModalUpdated() {
    updateModalTeacherData.value = false;
    updateModalClassData.value = false;
    resetForm();
}

onMounted(() => {
    getUserType();
    getTableTeacherData();
    getTableClassData();
    getTableStudentData();
});
</script>

<template>
    <LoadingComponent :isLoading="isLoading" />

    <ModalComponent v-if="showModalData" Titlevalue="Visualização de professores">
            <div class="modal-body-size">
                <h2>Detalhes sobre o professor</h2>
                <div class="modal-content-details">
                    <InputComponent
                        disabled="true"
                        labelTitle="Nome do professor"
                        placeholderValue="Nome da escola"
                        icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                        :value="formDataVisualize.name"
                        @input="formDataVisualize.name = $event.target.value"
                   />

                   <InputComponent
                        disabled="true"
                        labelTitle="Código de acesso"
                        placeholderValue="Código de acesso"
                        icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                        :value="formDataVisualize.acess_cod"
                        @input="formDataVisualize.acess_cod = $event.target.value"
                   />
                   <InputComponentPassword
                        disabled="true"
                        labelTitle="Senha de acesso"
                        placeholderValue="Senha"
                        icon="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                        :value="formDataVisualize.password"
                        @input="formDataVisualize.password = $event.target.value"
                        />
                </div>
                <div class="modal-content-address">

            </div>
       </div>
       <div class="modal-end">
            <a style="margin-right: 5rem;" class="close-modal" @click="closeModal()">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                </svg>
                Fechar
            </a>
        </div>
    </ModalComponent>

    <ModalComponent v-if="updateModalTeacherData" Titlevalue="Visualização de professores">
            <div class="modal-body-size">
                <h2>Detalhes sobre o professor</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Nome do professor"
                        placeholderValue="Nome da escola"
                        icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                        :value="formDataUpdate.name"
                        @input="formDataUpdate.name = $event.target.value"
                   />
                   <InputComponent
                        labelTitle="Código de acesso"
                        placeholderValue="Código de acesso"
                        icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                        :value="formDataUpdate.acess_cod"
                        @input="formDataUpdate.acess_cod = $event.target.value"
                   />
                   <InputComponentPassword
                        labelTitle="Senha de acesso"
                        placeholderValue="Senha"
                        icon="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                        :value="formDataUpdate.password"
                        @input="formDataUpdate.password = $event.target.value"
                        />
                </div>
                <div class="modal-content-address">

            </div>
       </div>
       <div class="modal-end">
            <a style="margin-right: 5rem;" class="close-modal" @click="closeModalUpdated()">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                </svg>
                Fechar
            </a>
            <a class="school-add" @click="updateDataForm()">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                </svg>
                Atualizar Professores
            </a>
        </div>
    </ModalComponent>

    <div class="school-register">

        <ModalComponent v-if="showModalCreation" Titlevalue="Cadastro de professores">
            <div class="modal-body-size">
                <h2>Detalhes sobre o professor</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Digite o nome completo do professor"
                        placeholderValue="Nome do professor"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataTeacherAdd.name"
                        typeValue="text"
                        @input="formDataTeacherAdd.name = $event.target.value"
                   />
                   <InputComponent
                        labelTitle="Acesso"
                        placeholderValue="Digite o acesso da escola"
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                        :value="formDataTeacherAdd.acess_cod"
                        RightAction="display: none;"
                        @input="formDataTeacherAdd.acess_cod = $event.target.value"
                        autocomplete="new-text"
                    />
                    <div class="input-password">
                        <InputComponentPassword
                        labelTitle="Senha de acesso"
                        placeholderValue="Senha"
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                        :value="formDataTeacherAdd.password"
                        @input="formDataTeacherAdd.password = $event.target.value"
                        typeValue="password"
                        RightAction="display: flex;"
                        />
                    </div>
                </div>
       </div>
       <div class="modal-end">
                <a class="close-modal" @click="closeModalAdd()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                    </svg>
                    Cancelar
                </a>
                <a class="school-add" @click="submitForm()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                    </svg>
                    Cadastrar Professores
                </a>
            </div>
        </ModalComponent>

        <ModalComponentDeleted v-if="deletedModal" Titlevalue="Apagar professor">
    <h1 class="deleted-title">
        Você realmente deseja apagar o professor <strong>{{ formDataDeleted.name }}</strong> ? Esta é uma ação permanente que não poderá ser desfeita
    </h1>
    <div class="modal-end-deleted">
        <a class="close-modal-deleted" @click="closeModalDeleted()">
            <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="var(--black-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
            </svg>
            Cancelar
        </a>
        <a ref="link" @click="deletedTeachers()">
            <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="red" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
            </svg>
            Apagar Professor
        </a>
    </div>
        </ModalComponentDeleted>

        <ModalComponent v-if="showModalClassData" Titlevalue="Visualização de professores">
            <div class="modal-body-size">
                <h2>Detalhes sobre o professor</h2>
                <div class="modal-content-details">
                    <InputComponent
                        disabled="true"
                        labelTitle="Nome da turma"
                        placeholderValue="Nome da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataClassVisualize.name"
                        typeValue="text"
                        @input="formDataClassVisualize.name = $event.target.value"
                   />
                   <SelectComponent
                        disabled="true"
                        labelTitle="Professor responsável da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="Teachers"
                        typeValue="select"
                        :value="formDataClassVisualize.teacher_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="formDataClassVisualize.teacher_id = $event.target.value"
                        />
                </div>
                <div class="modal-content-address">

            </div>
       </div>
       <div class="modal-end">
            <a style="margin-right: 5rem;" class="close-modal" @click="closeModal()">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                </svg>
                Fechar
            </a>
        </div>
        </ModalComponent>

        <ModalComponent v-if="ShowModalClassCreation" Titlevalue="Cadastro de turmas">
            <div class="modal-body-size">
                <h2>Detalhes sobre a turma</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Nome da turma"
                        placeholderValue="Nome da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataClassAdd.name"
                        typeValue="text"
                        @input="formDataClassAdd.name = $event.target.value"
                   />
                   <SelectComponent
                        labelTitle="Professor responsável da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="Teachers"
                        typeValue="select"
                        :value="formDataClassAdd.teacher_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="formDataClassAdd.teacher_id = $event.target.value"
                        />
                </div>
       </div>
       <div class="modal-end">
                <a class="close-modal" @click="closeModalAdd()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                    </svg>
                    Cancelar
                </a>
                <a class="school-add" @click="submitFormClass()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                    </svg>
                    Cadastrar Turma
                </a>
            </div>
        </ModalComponent>

        <ModalComponent v-if="updateModalClassData" Titlevalue="Atualizar professor">
            <div class="modal-body-size">
                <h2>Detalhes sobre o professor</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Nome da turma"
                        placeholderValue="Nome da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataClassVisualize.name"
                        typeValue="text"
                        @input="formDataClassVisualize.name = $event.target.value"
                   />
                   <SelectComponent
                        labelTitle="Professor responsável da turma"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="Teachers"
                        typeValue="select"
                        :value="formDataClassVisualize.teacher_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="formDataClassVisualize.teacher_id = $event.target.value"
                        />
                </div>
                <div class="modal-content-address"></div>
       </div>
       <div class="modal-end">
            <a style="margin-right: 5rem;" class="close-modal" @click="closeModalUpdated()">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                </svg>
                Fechar
            </a>
            <a class="school-add" @click="updateClassDataForm()">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                </svg>
                Atualizar Professores
            </a>
        </div>
        </ModalComponent>

        <ModalComponentDeleted v-if="deletedClassDataModal" Titlevalue="Apagar turma">
            <h1 class="deleted-title">
                Você realmente deseja apagar a turma <strong>{{ formDataClassDeleted.name }}</strong> ? Esta é uma ação permanente que não poderá ser desfeita
            </h1>
            <div class="modal-end-deleted">
                <a class="close-modal-deleted" @click="closeModalDeleted()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--black-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                    </svg>
                    Cancelar
                </a>
                <a ref="link" @click="deleteClassData()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="red" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                    </svg>
                    Apagar Professor
                </a>
            </div>
        </ModalComponentDeleted>

        <ModalComponent v-if="showModalStudentCreation" Titlevalue="Cadastro de professores">
            <div class="modal-body-size">
                <h2>Criação do Aluno</h2>
                <div class="modal-content-details">
                    <InputComponent
                        labelTitle="Nome do aluno"
                        placeholderValue="Nome do aluno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataStudentAdd.name"
                        typeValue="text"
                        @input="formDataStudentAdd.name = $event.target.value"
                   />

                   <SelectComponent
                        labelTitle="Turma do aluno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="ClassSchool"
                        typeValue="select"
                        :value="formDataStudentAdd.group_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="formDataStudentAdd.group_id = $event.target.value"
                        />
                </div>
       </div>
       <div class="modal-end">
                <a class="close-modal" @click="closeModalAdd()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                    </svg>
                    Cancelar
                </a>
                <a class="school-add" @click="submitStudentForm()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                    </svg>
                    Cadastrar Alunos
                </a>
            </div>
        </ModalComponent>
        
        <ModalComponent v-if="showModalStudentData" Titlevalue="Visualização de aluno">
            <div class="modal-body-size">
                <h2>Detalhes sobre o aluno</h2>
                <div class="modal-content-details">
                    <InputComponent
                        disabled="true"
                        labelTitle="Nome do aluno"
                        placeholderValue="Nome do aluno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataStudentVisualize.name"
                        typeValue="text"
                        @input="formDataStudentVisualize.name = $event.target.value"
                   />

                   <SelectComponent
                        disabled="true"
                        labelTitle="Turma do aluno"
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"
                        routerPath="ClassSchool"
                        typeValue="select"
                        :value="formDataStudentVisualize.group_id"
                        valueField="id"
                        RightAction="display: none;"
                        @input="formDataStudentVisualize.group_id = $event.target.value"
                        />
                </div>
                <div class="modal-content-address">

            </div>
       </div>
       <div class="modal-end">
            <a style="margin-right: 5rem;" class="close-modal" @click="closeModal()">
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                </svg>
                Fechar
            </a>
        </div>
        </ModalComponent>

        <userWelcomeComponent />
        <MenuComponent />

        <div class="register-content" v-if="userType === 'admin'">
            <TitleComponent title="Cadastro de escolas" />
            <TableComponent TitleValue="Escolas cadastradas" />
        </div>

        <div class="register-content" v-else-if="userType === 'school'">
            <TitleComponent title="Cadastro de Professores" />
            <tableComponentComponent
            TitleValue="Cadastrados"
            :TableHeader="['Professor', 'Acesso']"
            :TableContent="formDataTeachersPreview"
            :TableActions="true"
            :TableActionVisibility="true"
            :TableAddButton="true"
            :ButtonTitle="'Adicionar Professor'"
            :OpenAddModal="OpenModalTeachersCreation"
            @viewDetails="ShowSchoolTeachersData"
            @updateAction="UpdateSchoolTeachersData"
            @deletedAction="deletedModalTeachersShow"
        ></tableComponentComponent>

        <TitleComponent title="Cadastro de Turmas"/>
        <tableComponentComponent
        class="tableClass"
            TitleValue="Cadastrados"
            :TableHeader="['Turma', 'Professor responsável']"
            :TableContent="formDataClassPreview"
            :TableActions="true"
            :TableActionVisibility="true"
            :TableAddButton="true"
            :ButtonTitle="'Adicionar Turma'"
            :OpenAddModal="OpenModalClassCreation"
            @viewDetails="ShowSchoolClassData"
            @updateAction="UpdateSchoolClassData"
            @deletedAction="deletedModalClassShow"
        ></tableComponentComponent>
    </div>

    <div class="register-content" v-else-if="userType === 'teacher'">
            <TitleComponent title="Cadastro de alunos" />
            <tableComponentComponent
            TitleValue="Cadastrados"
            :TableHeader="['Nome do aluno', 'Turma']"
            :TableContent="formDataStudentPreview"
            :TableActions="true"
            :TableActionVisibility="true"
            :TableAddButton="true"
            :ButtonTitle="'Adicionar aluno'"
            :OpenAddModal="OpenModalStudentCreation"
            @viewDetails="ShowStudentData"
            @updateAction="UpdateSchoolTeachersData"
            @deletedAction="deletedModalTeachersShow"
        ></tableComponentComponent>
    </div>

    </div>

</template>

<style scoped>
    .modal-background {
        z-index: 9999;
    }

    .modal-end > .school-add {
        width: 16rem;
    }

</style>
