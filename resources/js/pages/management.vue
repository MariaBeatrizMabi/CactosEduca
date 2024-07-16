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

const emit = defineEmits(['viewDetails', 'updateAction', 'deletedAction']);

const userType = ref('');
const userID = ref('');
const showModalCreation = ref(false);
const ShowModalClassCreation = ref(false);
const isLoading = ref(false);

const showModalData = ref();
const updateModalData = ref();
const idToUpdate = ref(null);
const deletedModal = ref();
const idToDeleted = ref(null);

const formDataAdd = ref({
    name: '',
    school_id: userID.value,
    group_id: '',
    acess_cod: '',
    password: '',
    type: 'teacher'
})

let formDataTeachersPreview = ref({
    id: '',
    name: '',
    acess_cod: '',
    password: '',
})

let formDataClassPreview = ref({
    id: '',
    name: '',
    acess_cod: '',
    password: '',  
})

let formDataVisualize = ref({
    id: '',
    name: '',
    acess_cod: '',
    password: '',
})

let formDataUpdate = ref({
    id: '',
    name: '',
    acess_cod: '',
    password: '',
})

const OpenModalTeachersCreation = () => {
    showModalCreation.value = true;
};

const OpenModalClassCreation = () => {
    ShowModalClassCreation.value = true;
};

function resetForm() {
    formDataAdd.value = {
        name: '',
        school_id: userID.value,
        group_id: '',
        acess_cod: '',
        password: '',
        type: 'teacher'
    };
}

const getUserType = () => {
    axios.get('/loginUser').then(response => {
        userType.value = response.data.type;
        userID.value = response.data.id;

         formDataAdd.value = {
            name: '',
            school_id: userID.value,
            group_id: '',
            acess_cod: '',
            password: '',
            type: 'teacher'
        };

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
            acess_cod: take.acess_cod,
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
            acess_cod: take.acess_cod,
        }));

    } catch (error) {
        console.error(error);
    }
}

async function submitForm() {
    try {
        isLoading.value = true;  
        await axios.post('/TeacherCreate', formDataAdd.value);
        
        getTableTeacherData()
        
        ShowModalClassCreation.value = false;

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
        await axios.post('/ClassCreate', formDataAdd.value);
        
        getTableClassData();
        
        showModalCreation.value = false;

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
        const response = await axios.get(`/Teachers`);

        const teacher = response.data.find(teacher => teacher.id === id);
        
        if (teacher) {
            formDataVisualize.value = {
                id: teacher.id,
                name: teacher.name,
                acess_cod: teacher.acess_cod,
                password: teacher.password,
                type: 'teacher'
            };
        } else {
            console.error(`Não foi possível encontrar o professor com o ID ${id}`);
        }

    } catch (error) {
        console.error(error);
    }
}

async function UpdateSchoolTeachersData(id) {
    updateModalData.value = true;
    try {
        const response = await axios.get(`/Teachers/${id}`);
        console.log("CLICK", response);

        const teacher = response.data.find(teacher => teacher.id === id);

        idToUpdate.value = id;

        if (teacher) {
            formDataUpdate.value = {
                id: teacher.id,
                name: teacher.name,
                acess_cod: teacher.acess_cod,
                password: teacher.password, 
                type: 'teacher'
            };
        } else {
            console.error(`Não foi possível encontrar o professor com o ID ${id}`);
        }

    } catch (error) {
        console.error(error);
    }
}

async function updateDataForm() {
    const id = idToUpdate.value; 
    try {
        const response = await axios.put(`/Teachers/${id}`, formDataUpdate.value); 

        updateModalData.value = false;
        isLoading.value = true;

        getTableTeacherData(); 

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
        

        idToDeleted.value = id;

        formDataVisualize.value = {
            name: response.data.name,
        };
    } catch (error) {
        console.error('Erro ao buscar dados do professor:', error);
    }
}


async function deleted() {
    const id = idToDeleted.value; 

    try {
        var response = await axios.delete(`/Teachers/${id}`); 
        deletedModal.value = false;
        isLoading.value = true;
        console.log(response);

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

function closeModalDeleted() {
    resetForm();
    deletedModal.value = false;
}

function closeModalAdd() {
    showModalCreation.value = false;
    resetForm();
}

function closeModal() {
    showModalData.value = false;
    resetForm();
}

function closeModalUpdated() {
    updateModalData.value = false;
    resetForm();
}

onMounted(() => {
    getUserType();
    getTableTeacherData();
    getTableClassData();
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

    <ModalComponent v-if="updateModalData" Titlevalue="Visualização de professores">
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
                        :value="formDataAdd.name"
                        typeValue="text"
                        @input="formDataAdd.name = $event.target.value" 
                   />
                   <InputComponent 
                        labelTitle="Acesso" 
                        placeholderValue="Digite o acesso da escola"  
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                        :value="formDataAdd.acess_cod"
                        RightAction="display: none;"
                        @input="formDataAdd.acess_cod = $event.target.value" 
                        autocomplete="new-text"
                    />
                    <div class="input-password">
                        <InputComponentPassword 
                        labelTitle="Senha de acesso" 
                        placeholderValue="Senha"  
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                        :value="formDataAdd.password"
                        @input="formDataAdd.password = $event.target.value" 
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

        <ModalComponent v-if="ShowModalClassCreation" Titlevalue="Cadastro de turmas">
            <div class="modal-body-size">
                <h2>Detalhes sobre a turma</h2>
                <div class="modal-content-details">
                    <InputComponent 
                        labelTitle="Nome da turma" 
                        placeholderValue="Nome da turma"  
                        icon="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"
                        :value="formDataAdd.name"
                        typeValue="text"
                        @input="formDataAdd.name = $event.target.value" 
                   />
                   <InputComponent 
                        labelTitle="Professor responsável da turma" 
                        placeholderValue="Professor responsável da turma"  
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                        :value="formDataAdd.acess_cod"
                        RightAction="display: none;"
                        @input="formDataAdd.acess_cod = $event.target.value" 
                        autocomplete="new-text"
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

        <ModalComponentDeleted v-if="deletedModal" Titlevalue="Apagar professor">
    <h1 class="deleted-title">
        Você realmente deseja apagar o professor<strong>{{ formDataVisualize.name }}</strong>? Esta é uma ação permanente que não poderá ser desfeita
    </h1>
    <div class="modal-end-deleted">
        <a class="close-modal-deleted" @click="closeModalDeleted()">
            <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="var(--black-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
            </svg>
            Cancelar
        </a>
        <a ref="link" @click="deleted()">
            <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="red" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
            </svg>
            Apagar Professor
        </a>
    </div>
</ModalComponentDeleted>
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
            TitleValue="Cadastrados" 
            :TableHeader="['Turma', 'Professor responsável']"
            :TableContent="formDataClassPreview"
            :TableActions="true"
            :TableActionVisibility="true"
            :TableAddButton="true"
            :ButtonTitle="'Adicionar Turma'"
            :OpenAddModal="OpenModalClassCreation"
            @viewDetails="ShowSchoolTeachersData"
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