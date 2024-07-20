<script setup>
import { defineEmits, ref, onMounted, defineProps } from 'vue';
import axios from 'axios';
import ModalComponent from '../components/modal.vue'
import InputComponent from '../components/input.vue'
import LoadingComponent from '../components/loading.vue'
import ModalComponentDeleted from '../components/modalComponentShort.vue';
import InputComponentPassword from '../components/inputPassword.vue'
import SelectComponent from '../components/select.vue'

const showModal = ref();
const showModalUpdated = ref();
const showModalData = ref();
const deletedModal = ref();

const idToUpdate = ref(null);
const idToDeleted = ref(null);

const isLoading = ref(false);
const ChangeType = ref();

const props = defineProps({
    TitleValue: {
        type: String,
        required: true
    }
})

function resetForm() {
    formDataAdd.value = {
        name: '',
        address: '',
        city: '',
        zip_code: '',
        acess_cod: '',
        password: '',
        type: 'school'
    };
}

let formData = ref({
    id: '',
    name: '',
    address: '',
    acess_cod: '',
    city: '',
    zip_code: '',
    password: '',
})

const formDataAdd = ref({
    name: '',
    address: '',
    city: '',
    zip_code: '',
    acess_cod: '',
    password: '',
    type: 'school'
})

let formDataUpdate = ref({
    id: '',
    name: '',
    address: '',
    acess_cod: '',
    city: '',
    zip_code: '',
    password: '',
})

let formDataShow = ref({
    id: '',
    name: '',
    address: '',
    acess_cod: '',
    city: '',
    zip_code: '',
    password: '',
})

async function getTableData() {
    try {
        const response = await axios('/ManagementSchool');
        formData.value = response.data.map(take => ({
            id: take.id,
            name: take.name,
            city: take.city,
            acess_cod: take.acess_cod,
        }));
    } catch (error) {
        console.error(error);
    }
}

async function ShowSchoolData(id) {
    showModalData.value = true;
    try {
        const response = await axios.get(`/ManagementSchool/${id}`);
        
        formDataShow.value = {
            id: response.data.id,
            name: response.data.name,
            address: response.data.address,
            city: response.data.city,
            zip_code: response.data.zip_code,
            acess_cod: response.data.acess_cod,
            password: response.data.password,
            type: 'school'
        };

    } catch (error) {
        console.error(error);
    }
}


function openModal() {
    resetForm();
    showModal.value = true;
}

function closeModal() {
    resetForm();
    showModalUpdated.value = false;
}

function closeModalAdd() {
    resetForm();
    showModal.value = false;
}

function closeModalShow() {
    resetForm(); 
    showModalData.value = false;
}

function closeModalDeleted() {
    resetForm();
    deletedModal.value = false;
}

async function submitForm() {
    try {
        isLoading.value = true;  
        await axios.post('/ManagementSchoolCreate', formDataAdd.value);
        
        getTableData()
        
        showModal.value = false;

        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error)
        isLoading.value = false;
    }
}

async function fetchSchoolData(id) {
    showModalUpdated.value = true;
    try {
        const response = await axios.get(`/ManagementSchool/${id}`);
        
        idToUpdate.value = id;

        formDataUpdate.value = {
            id: response.data.id,
            name: response.data.name,
            address: response.data.address,
            city: response.data.city,
            zip_code: response.data.zip_code,
            acess_cod: response.data.acess_cod,
            password: response.data.password,
            type: 'school'
        };

    } catch (error) {
        console.error(error);
    }
}
 
async function updateDataForm(dataToUpdate) {
    const id = idToUpdate.value; 
    try {
        const response = await axios.put(`/ManagementSchool/${id}`, formDataUpdate.value); 
        showModalUpdated.value = false;
        isLoading.value = true;
    
        getTableData() 
    
        setTimeout(() => {
            isLoading.value = false;
        }, 800);
    } catch (error) {
        console.error(error);
    }
}

async function deletedModalShow(id) {
    deletedModal.value = true;
    resetForm();
    const response = await axios.get(`/ManagementSchool/${id}`);

    idToDeleted.value = id;
        
    formDataShow.value = {
        name: response.data.name,
    };
}

async function deleted() {
    const id = idToDeleted.value; 

    try {
        var response = await axios.delete(`/ManagementSchool/${id}`); 
        deletedModal.value = false;
        isLoading.value = true;

        getTableData()
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

onMounted(
    isLoading.value = true,
    getTableData(),
    setTimeout(() => {
            isLoading.value = false;
        }, 800),
);

</script>
<template>
    <LoadingComponent :isLoading="isLoading" />
    <ModalComponent v-if="showModalData" Titlevalue="Visualização de escolas">
            <div class="modal-body-size">
                <h2>Detalhes sobre a escola</h2>
                <div class="modal-content-details">
                    <InputComponent
                        disabled="true"
                        labelTitle="Nome da escola" 
                        placeholderValue="Nome da escola"  
                        icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                        :value="formDataShow.name"
                        @input="formDataShow.name = $event.target.value" 
                   />
                    <InputComponent 
                        disabled="true"
                        labelTitle="Acesso" 
                        placeholderValue="Digite o acesso da escola"  
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
                        :value="formDataShow.acess_cod"
                        @input="formDataShow.acess_cod = $event.target.value" 
                    />
                    <InputComponentPassword 
                        disabled="true"
                        labelTitle="Senha de acesso" 
                        placeholderValue="Senha"  
                        icon="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                        :value="formDataShow.password"
                        @input="formDataShow.password = $event.target.value" 
                        />
                </div>
                <h2>Localização da escola</h2>
                <div class="modal-content-address">
                    <InputComponent
                        disabled="true" 
                        labelTitle="Múnicipio da escola" 
                        placeholderValue="Digite o múnicipio"  
                        icon="M408 120c0 54.6-73.1 151.9-105.2 192c-7.7 9.6-22 9.6-29.6 0C241.1 271.9 168 174.6 168 120C168 53.7 221.7 0 288 0s120 53.7 120 120zm8 80.4c3.5-6.9 6.7-13.8 9.6-20.6c.5-1.2 1-2.5 1.5-3.7l116-46.4C558.9 123.4 576 135 576 152V422.8c0 9.8-6 18.6-15.1 22.3L416 503V200.4zM137.6 138.3c2.4 14.1 7.2 28.3 12.8 41.5c2.9 6.8 6.1 13.7 9.6 20.6V451.8L32.9 502.7C17.1 509 0 497.4 0 480.4V209.6c0-9.8 6-18.6 15.1-22.3l122.6-49zM327.8 332c13.9-17.4 35.7-45.7 56.2-77V504.3L192 449.4V255c20.5 31.3 42.3 59.6 56.2 77c20.5 25.6 59.1 25.6 79.6 0zM288 152a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"
                        :value="formDataShow.city"
                        @input="formDataShow.city = $event.target.value" 
                        />
                    <InputComponent 
                        disabled="true"
                        labelTitle="CEP" 
                        placeholderValue="Digite o CEP da escola"  
                        icon="M320 64A64 64 0 1 0 192 64a64 64 0 1 0 128 0zm-96 96c-35.3 0-64 28.7-64 64v48c0 17.7 14.3 32 32 32h1.8l11.1 99.5c1.8 16.2 15.5 28.5 31.8 28.5h38.7c16.3 0 30-12.3 31.8-28.5L318.2 304H320c17.7 0 32-14.3 32-32V224c0-35.3-28.7-64-64-64H224zM132.3 394.2c13-2.4 21.7-14.9 19.3-27.9s-14.9-21.7-27.9-19.3c-32.4 5.9-60.9 14.2-82 24.8c-10.5 5.3-20.3 11.7-27.8 19.6C6.4 399.5 0 410.5 0 424c0 21.4 15.5 36.1 29.1 45c14.7 9.6 34.3 17.3 56.4 23.4C130.2 504.7 190.4 512 256 512s125.8-7.3 170.4-19.6c22.1-6.1 41.8-13.8 56.4-23.4c13.7-8.9 29.1-23.6 29.1-45c0-13.5-6.4-24.5-14-32.6c-7.5-7.9-17.3-14.3-27.8-19.6c-21-10.6-49.5-18.9-82-24.8c-13-2.4-25.5 6.3-27.9 19.3s6.3 25.5 19.3 27.9c30.2 5.5 53.7 12.8 69 20.5c3.2 1.6 5.8 3.1 7.9 4.5c3.6 2.4 3.6 7.2 0 9.6c-8.8 5.7-23.1 11.8-43 17.3C374.3 457 318.5 464 256 464s-118.3-7-157.7-17.9c-19.9-5.5-34.2-11.6-43-17.3c-3.6-2.4-3.6-7.2 0-9.6c2.1-1.4 4.8-2.9 7.9-4.5c15.3-7.7 38.8-14.9 69-20.5z"                    
                        :value="formDataShow.zip_code"
                        @input="formDataShow.zip_code = $event.target.value" 
                        />
                    <InputComponent 
                        disabled="true"
                        labelTitle="Bairro" 
                        placeholderValue="Digite o bairro da escola"  
                        icon="M48 0C21.5 0 0 21.5 0 48V464c0 26.5 21.5 48 48 48h96V432c0-26.5 21.5-48 48-48s48 21.5 48 48v80h89.9c-6.3-10.2-9.9-22.2-9.9-35.1c0-46.9 25.8-87.8 64-109.2V271.8 48c0-26.5-21.5-48-48-48H48zM64 240c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V240zm112-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V240c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V240zM80 96h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V112zM272 96h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16zM576 272a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM352 477.1c0 19.3 15.6 34.9 34.9 34.9H605.1c19.3 0 34.9-15.6 34.9-34.9c0-51.4-41.7-93.1-93.1-93.1H445.1c-51.4 0-93.1 41.7-93.1 93.1z"
                        :value="formDataShow.address"
                        @input="formDataShow.address = $event.target.value" 
                    />
            </div>
       </div>
       <div class="modal-end">
                <a style="margin-right: 5rem;" class="close-modal" @click="closeModalShow()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                    </svg>
                    Fechar 
                </a>
            </div>
    </ModalComponent>

    <ModalComponent v-if="showModal" Titlevalue="Cadastro de escolas">
            <div class="modal-body-size">
                <h2>Detalhes sobre a escola</h2>
                <div class="modal-content-details">
                    <InputComponent 
                        labelTitle="Nome da escola" 
                        placeholderValue="Nome da escola"  
                        icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                        :value="formDataAdd.name"
                        typeValue="text"
                        RightAction="display: none;"
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
                <h2>Localização da escola</h2>
                <div class="modal-content-address">
                    <SelectComponent 
                        labelTitle="Múnicipio da escola" 
                        placeholderValue="Digite o múnicipio"  
                        icon="M408 120c0 54.6-73.1 151.9-105.2 192c-7.7 9.6-22 9.6-29.6 0C241.1 271.9 168 174.6 168 120C168 53.7 221.7 0 288 0s120 53.7 120 120zm8 80.4c3.5-6.9 6.7-13.8 9.6-20.6c.5-1.2 1-2.5 1.5-3.7l116-46.4C558.9 123.4 576 135 576 152V422.8c0 9.8-6 18.6-15.1 22.3L416 503V200.4zM137.6 138.3c2.4 14.1 7.2 28.3 12.8 41.5c2.9 6.8 6.1 13.7 9.6 20.6V451.8L32.9 502.7C17.1 509 0 497.4 0 480.4V209.6c0-9.8 6-18.6 15.1-22.3l122.6-49zM327.8 332c13.9-17.4 35.7-45.7 56.2-77V504.3L192 449.4V255c20.5 31.3 42.3 59.6 56.2 77c20.5 25.6 59.1 25.6 79.6 0zM288 152a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"
                        typeValue="select"
                        :value="formDataAdd.city"
                        routerPath="counties"
                        RightAction="display: none;"
                        @input="formDataAdd.city = $event.target.value" 
                        />
                    <InputComponent 
                        labelTitle="CEP" 
                        placeholderValue="Digite o CEP da escola"  
                        icon="M320 64A64 64 0 1 0 192 64a64 64 0 1 0 128 0zm-96 96c-35.3 0-64 28.7-64 64v48c0 17.7 14.3 32 32 32h1.8l11.1 99.5c1.8 16.2 15.5 28.5 31.8 28.5h38.7c16.3 0 30-12.3 31.8-28.5L318.2 304H320c17.7 0 32-14.3 32-32V224c0-35.3-28.7-64-64-64H224zM132.3 394.2c13-2.4 21.7-14.9 19.3-27.9s-14.9-21.7-27.9-19.3c-32.4 5.9-60.9 14.2-82 24.8c-10.5 5.3-20.3 11.7-27.8 19.6C6.4 399.5 0 410.5 0 424c0 21.4 15.5 36.1 29.1 45c14.7 9.6 34.3 17.3 56.4 23.4C130.2 504.7 190.4 512 256 512s125.8-7.3 170.4-19.6c22.1-6.1 41.8-13.8 56.4-23.4c13.7-8.9 29.1-23.6 29.1-45c0-13.5-6.4-24.5-14-32.6c-7.5-7.9-17.3-14.3-27.8-19.6c-21-10.6-49.5-18.9-82-24.8c-13-2.4-25.5 6.3-27.9 19.3s6.3 25.5 19.3 27.9c30.2 5.5 53.7 12.8 69 20.5c3.2 1.6 5.8 3.1 7.9 4.5c3.6 2.4 3.6 7.2 0 9.6c-8.8 5.7-23.1 11.8-43 17.3C374.3 457 318.5 464 256 464s-118.3-7-157.7-17.9c-19.9-5.5-34.2-11.6-43-17.3c-3.6-2.4-3.6-7.2 0-9.6c2.1-1.4 4.8-2.9 7.9-4.5c15.3-7.7 38.8-14.9 69-20.5z"                    
                        RightAction="display: none;"
                        :value="formDataAdd.zip_code"
                        @input="formDataAdd.zip_code = $event.target.value" 
                        />
                    <InputComponent 
                        labelTitle="Bairro" 
                        placeholderValue="Digite o bairro da escola"  
                        icon="M48 0C21.5 0 0 21.5 0 48V464c0 26.5 21.5 48 48 48h96V432c0-26.5 21.5-48 48-48s48 21.5 48 48v80h89.9c-6.3-10.2-9.9-22.2-9.9-35.1c0-46.9 25.8-87.8 64-109.2V271.8 48c0-26.5-21.5-48-48-48H48zM64 240c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V240zm112-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V240c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V240zM80 96h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V112zM272 96h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16zM576 272a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM352 477.1c0 19.3 15.6 34.9 34.9 34.9H605.1c19.3 0 34.9-15.6 34.9-34.9c0-51.4-41.7-93.1-93.1-93.1H445.1c-51.4 0-93.1 41.7-93.1 93.1z"
                        RightAction="display: none;"
                        :value="formDataAdd.address"
                        @input="formDataAdd.address = $event.target.value" 
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
                <a class="school-add" @click="submitForm()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                    </svg>
                    Cadastrar Escola
                </a>
            </div>
    </ModalComponent>

    <ModalComponent v-if="showModalUpdated" Titlevalue="Atualização de escolas">
            <div class="modal-body-size">
                <h2>Detalhes sobre a escola</h2>
                <div class="modal-content-details">
                    <InputComponent 
                        labelTitle="Nome da escola" 
                        placeholderValue="Nome da escola"  
                        icon="M337.8 5.4C327-1.8 313-1.8 302.2 5.4L166.3 96H48C21.5 96 0 117.5 0 144V464c0 26.5 21.5 48 48 48H256V416c0-35.3 28.7-64 64-64s64 28.7 64 64v96H592c26.5 0 48-21.5 48-48V144c0-26.5-21.5-48-48-48H473.7L337.8 5.4zM96 192h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V208c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V208zM96 320h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V336c0-8.8 7.2-16 16-16zm400 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v64c0 8.8-7.2 16-16 16H512c-8.8 0-16-7.2-16-16V336zM232 176a88 88 0 1 1 176 0 88 88 0 1 1 -176 0zm88-48c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H336V144c0-8.8-7.2-16-16-16z"
                        :value="formDataUpdate.name"
                        @input="formDataUpdate.name = $event.target.value" 
                   />
                    <InputComponent 
                        labelTitle="Acesso" 
                        placeholderValue="Digite o acesso da escola"  
                        icon="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"
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
                <h2>Localização da escola</h2>
                <div class="modal-content-address">
                    <SelectComponent 
                        labelTitle="Múnicipio da escola" 
                        placeholderValue="Digite o múnicipio"  
                        icon="M408 120c0 54.6-73.1 151.9-105.2 192c-7.7 9.6-22 9.6-29.6 0C241.1 271.9 168 174.6 168 120C168 53.7 221.7 0 288 0s120 53.7 120 120zm8 80.4c3.5-6.9 6.7-13.8 9.6-20.6c.5-1.2 1-2.5 1.5-3.7l116-46.4C558.9 123.4 576 135 576 152V422.8c0 9.8-6 18.6-15.1 22.3L416 503V200.4zM137.6 138.3c2.4 14.1 7.2 28.3 12.8 41.5c2.9 6.8 6.1 13.7 9.6 20.6V451.8L32.9 502.7C17.1 509 0 497.4 0 480.4V209.6c0-9.8 6-18.6 15.1-22.3l122.6-49zM327.8 332c13.9-17.4 35.7-45.7 56.2-77V504.3L192 449.4V255c20.5 31.3 42.3 59.6 56.2 77c20.5 25.6 59.1 25.6 79.6 0zM288 152a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"
                        routerPath="counties"
                        :value="formDataUpdate.city"
                        @input="formDataUpdate.city = $event.target.value" 
                        />
                    <InputComponent 
                        labelTitle="CEP" 
                        placeholderValue="Digite o CEP da escola"  
                        icon="M320 64A64 64 0 1 0 192 64a64 64 0 1 0 128 0zm-96 96c-35.3 0-64 28.7-64 64v48c0 17.7 14.3 32 32 32h1.8l11.1 99.5c1.8 16.2 15.5 28.5 31.8 28.5h38.7c16.3 0 30-12.3 31.8-28.5L318.2 304H320c17.7 0 32-14.3 32-32V224c0-35.3-28.7-64-64-64H224zM132.3 394.2c13-2.4 21.7-14.9 19.3-27.9s-14.9-21.7-27.9-19.3c-32.4 5.9-60.9 14.2-82 24.8c-10.5 5.3-20.3 11.7-27.8 19.6C6.4 399.5 0 410.5 0 424c0 21.4 15.5 36.1 29.1 45c14.7 9.6 34.3 17.3 56.4 23.4C130.2 504.7 190.4 512 256 512s125.8-7.3 170.4-19.6c22.1-6.1 41.8-13.8 56.4-23.4c13.7-8.9 29.1-23.6 29.1-45c0-13.5-6.4-24.5-14-32.6c-7.5-7.9-17.3-14.3-27.8-19.6c-21-10.6-49.5-18.9-82-24.8c-13-2.4-25.5 6.3-27.9 19.3s6.3 25.5 19.3 27.9c30.2 5.5 53.7 12.8 69 20.5c3.2 1.6 5.8 3.1 7.9 4.5c3.6 2.4 3.6 7.2 0 9.6c-8.8 5.7-23.1 11.8-43 17.3C374.3 457 318.5 464 256 464s-118.3-7-157.7-17.9c-19.9-5.5-34.2-11.6-43-17.3c-3.6-2.4-3.6-7.2 0-9.6c2.1-1.4 4.8-2.9 7.9-4.5c15.3-7.7 38.8-14.9 69-20.5z"                    
                        :value="formDataUpdate.zip_code"
                        @input="formDataUpdate.zip_code = $event.target.value" 
                        />
                    <InputComponent 
                        labelTitle="Bairro" 
                        placeholderValue="Digite o bairro da escola"  
                        icon="M48 0C21.5 0 0 21.5 0 48V464c0 26.5 21.5 48 48 48h96V432c0-26.5 21.5-48 48-48s48 21.5 48 48v80h89.9c-6.3-10.2-9.9-22.2-9.9-35.1c0-46.9 25.8-87.8 64-109.2V271.8 48c0-26.5-21.5-48-48-48H48zM64 240c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V240zm112-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V240c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V240zM80 96h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16zm80 16c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H176c-8.8 0-16-7.2-16-16V112zM272 96h32c8.8 0 16 7.2 16 16v32c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16zM576 272a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM352 477.1c0 19.3 15.6 34.9 34.9 34.9H605.1c19.3 0 34.9-15.6 34.9-34.9c0-51.4-41.7-93.1-93.1-93.1H445.1c-51.4 0-93.1 41.7-93.1 93.1z"
                        :value="formDataUpdate.address"
                        @input="formDataUpdate.address = $event.target.value" 
                    />
            </div>
       </div>
       <div class="modal-end">
                <a class="close-modal" @click="closeModal()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="red" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                    </svg>
                    Cancelar
                </a>
                <a ref="link" @click="updateDataForm()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                    </svg>
                    Atualizar Escola
                </a>
            </div>
    </ModalComponent>

    <ModalComponentDeleted v-if="deletedModal" Titlevalue="Apagar escola">
            <h1 class="deleted-title">Você realmente deseja apagar a escola <strong> {{ formDataShow.name }}?</strong> 
                Esta é uma ação permantente que não poderá ser desfeita</h1>
        <div class="modal-end-deleted">
                <a class="close-modal-deleted" @click="closeModalDeleted()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="var(--black-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"></path>
                    </svg>
                    Cancelar
                </a>
                <a ref="link" @click="deleted()">
                    <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="red"  d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                    </svg>
                    Apagar Escola
                </a>
            </div>
    </ModalComponentDeleted>


<div class="tableContent">
   <div class="showModalContent">
        <a class="showModalInfo" @click="openModal()">
            <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="var(--secondary-color)" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
            </svg>
            Adicionar Escola
        </a>
   </div>

    <div class="titleTable">
        <h1>{{ TitleValue }}</h1>
    </div>
    <table>
        <tr>
            <th>Nome</th>
            <th class="address">Endereço</th>
            <th>Código de acesso</th>
            <th>Ações</th>
        </tr>
        <tr v-for="(data, index) in formData" :key="index">
            <td> {{ data.name }}</td>
            <td class="address"> {{ data.city }} </td>
            <td> {{ data.acess_cod }} </td>
            <td>
                <div class="actions">
                    <div class="show" @click="ShowSchoolData(data.id)">
                        <svg width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/>
                        </svg>
                    </div>
                    <div class="edit" @click="fetchSchoolData(data.id)">
                        <svg width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/>
                        </svg>
                    </div>
                    <div class="deleted" @click="deletedModalShow(data.id)">
                        <svg width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"></path> 
                        </svg>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
</template>

<style>
.modal-body-size {
    width: 80%;
    color: var(--black-color);
}

.modal-content-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    margin: 2rem 0;
    width: 100%;
}

.modal-content-address {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    width: 100%;
    gap: 2rem 5rem;
    margin: 2rem 0;
}

.modal-end, .modal-end-deleted {
    width: 100%;
    background-color: white;
    
    border-radius: 0 0 5rem 5rem;
    
    display: flex;
    
    justify-content: end;
    border-top: 2px solid var(--primary-color);

    & .close-modal{
            border: 1px solid red;
            color: red;
            width: 10rem;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 1rem 2rem 1rem 0;
        }

        & .close-modal:hover{
            transition: 0.3s;
            border: 1px solid white;
            color: white;
            background-color: red;

            & svg > path {
                fill: white;
        }     
        }

        & a {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 1rem 5rem 1rem 0;
            display: flex;
            align-items: end;
            width: 13rem;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            border: 1px solid var(--secondary-color);
            background-color: white;
            align-items: center;
            color: var(--secondary-color);
            cursor: pointer;
        }

        a:hover {
            transition: 0.3s;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            & svg > path {
                fill: var(--primary-color);
        } 
    }
}

.modal-end-deleted {
    border-top: 2px solid var(--black-color) !important;
    & a {
            border: 1px solid red;
            background-color: white;
            color: red;
        }

        a:hover {
            transition: 0.3s;
            color: white;
            background-color: red;
            border: 1px solid red;
            & svg > path {
                fill: white;
        } 
    }

    & .close-modal-deleted{
            border: 1px solid var(--black-color);
            color: var(--black-color);
            width: 10rem;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 1rem 2rem 1rem 0;
        }

        & .close-modal-deleted:hover{
            transition: 0.3s;
            border: 1px solid white;
            color: white;
            background-color: var(--black-color);

            & svg > path {
                fill: white;
        }    
    } 
}

.input-password {
    display: flex;

    .input-formating{
        border-radius: 5rem !important;
    }   

    & svg:last-child {
        background-color: var(--grap-color);
        border-radius: 5rem;
        width: 5.5rem;
        padding: 1.5rem;
    }
}

.deleted-title {
    color: red;
    width: 90%;
    font-size: 20px;
    padding: 2rem 0;
    font-weight: 400;
}

.tableContent {
    width: 84%;
    display: flex;
    flex-direction: column;
    padding: 0 0 5rem 0;
    justify-content: start;
}

.showModalContent {
    display: flex;
    justify-content: end;
    margin: 1rem 0 1rem 0;
}

.showModalInfo {
    display: flex;
    align-items: end;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    justify-content: center;
    border-radius: 2rem;
    border: 1px solid var(--secondary-color);
    background-color: white;
    align-items: center;
    color: var(--secondary-color);
    cursor: pointer;
}

.showModalInfo:hover {
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
     & svg > path {
        fill: var(--primary-color);
     }
}

.titleTable {
    background-color: var(--secondary-color);
    z-index: -1000;

    color: white;
    width: 100%;    
    text-align: center;

    padding: 0.5rem;
    font-size: 12px;

    border-radius: 1rem 1rem 0 0;
}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
  
  background-color: rgb(255, 255, 255);

  & tr > th{
        padding: 1.5rem 1.5rem 1.5rem 1rem;  
    }
}

td, th {
  border: 1px solid var(--secondary-color);
  text-align: left;
  padding: 8px 1rem;
}

th:last-child{
    text-align: center;
}

tr:nth-child(even) {
  background-color: var(--grap-color);
}

.actions {
    display: flex;
    justify-content: center;
    gap: 3rem;

    & .show{
        display: flex;
        justify-content: center;
        padding: 0.5rem 1rem;
        width: 3.5rem;
        border-radius: 100%;
        cursor: pointer;
        & svg > path {
            fill: var(--primary-color);            
        }
    }

    &  .show:hover {
        background-color:  var(--primary-color);
        & svg > path {
            fill: rgb(255, 255, 255);            
        }
    }

    & .edit {
        display: flex;
        justify-content: center;
        padding: 0.5rem 1rem;
        width: 3.5rem;
        border-radius: 100%;
        cursor: pointer;
        & svg > path {
            fill: rgb(47, 0, 255);            
        }
    }

    &  .edit:hover {
        background-color: rgb(47, 0, 255);
        & svg > path {
            fill: rgb(255, 255, 255);            
        }
    }

    & .deleted {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 3.5rem;
        width: 3.5rem;
        border-radius: 100%;
        cursor: pointer;
        & svg > path {
            fill: rgb(255, 0, 0);            
        }
    }

    &  .deleted:hover {
        background-color: rgb(255, 0, 0);
        & svg > path {
            fill: rgb(255, 255, 255);            
        }
    }
}

@media (max-width: 700px) {
.modal-content-details {
    grid-template-columns: repeat(1, 1fr);
    gap: 1rem;
    margin: 1rem 0;
}
.modal-content-address{
    grid-template-columns: repeat(1, 1fr);
    gap: 1rem;
    margin: 1rem 0;
}

.deleted-title{
    font-size: 16px;
}

.modal-end-deleted {
   & .close-modal-deleted {
        margin: 1rem 0;
        width: 70%;
    }
}

.modal-background-deleted {
  & .modal-content {
    height: 35%;
  }
}

.modal-end, .modal-end-deleted {
    flex-direction: column;
    align-items: center;
        & a {
            margin: 1rem 0;
            display: flex;
            align-items: end;
            width: 70%;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
        }

        & .close-modal {
            width: 70%;
            margin-right: 0 !important;
            margin: 1rem 0;
        }
    }
    .address {
    display: none;
}
}

@media (max-width: 550px) {
    .actions {
    gap: 1rem;
    flex-direction: column;
    align-items: center;

    & .show {
        padding: 1rem;
    }

    & .edit {
        padding: 1rem;
    }

    & .deleted {
        padding: 1rem;
    }
    }
}
</style>