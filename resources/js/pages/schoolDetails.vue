<script setup>
import MenuComponent from '../components/menu.vue'
import TitleComponent from '/resources/js/components/title.vue';
import UserWelcomeComponet from '/resources/js/components/userWelcome.vue';
import ChartBarBimReading from '/resources/js/components/chartBarBimReading.vue';
import ChartBarBimWriting from '/resources/js/components/chartBarBimWriting.vue';
</script>

<template>
    <div class="dashboard">
        <MenuComponent />    
        <div class="dashboard-content">
            <UserWelcomeComponet class="welcome-component"></UserWelcomeComponet>
            <TitleComponent title="Análise geral média escolas - Leitura"/>
            <ChartBarBimReading titleGrapichCard="Nível geral das escolas - Leitura" />
            <TitleComponent title="Análise geral média escolas - Escrita"/>
            <ChartBarBimWriting titleGrapichCard="Nível geral das escolas - Leitura" />

        </div>
    </div>
</template>

<style>
.dashboard {
    display: flex;
    height: 100vh;

    & .dashboard-content{
        display: flex;
        align-items: center;
        margin: 7rem 0;
        width: 100%;
        flex-direction: column;
    }
}
</style>