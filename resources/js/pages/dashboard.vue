<script setup>
import menuCompoent from '/resources/js/components/menu.vue';
import TitleComponent from '/resources/js/components/title.vue';
import CardComponent from '/resources/js/components/card.vue';
import CardgrapichComponent from '/resources/js/components/cardGraphic.vue';
import UserWelcomeComponet from '/resources/js/components/userWelcome.vue';

</script>

<template>
    <div class="dashboard">
        <menuCompoent />
        <div class="dashboard-content">
        <UserWelcomeComponet class="welcome-component"></UserWelcomeComponet>
            <TitleComponent title="Análise Geral"/>
            <div class="Cards-container">
                <CardComponent imageCard="school.gif" titleCard="Escolas Cadastradas" valueCard="1030" :ref="CardValue"></CardComponent>
                <CardComponent imageCard="groupSchool.gif" titleCard="Turmas Cadastradas" valueCard="1.566"></CardComponent>
                <CardComponent imageCard="student.gif" titleCard="Professores Cadastradas" valueCard="1.932"></CardComponent>
                <CardComponent imageCard="teacher.gif" titleCard="Alunos Cadastradas" valueCard="3.455"></CardComponent>
            </div>

            <TitleComponent title="Escolas Cadastradas"/>
            <CardgrapichComponent titleGrapichCard="Cadastrados Por Múnicipio" ></CardgrapichComponent>
        </div>
    </div>
</template>

<style>
    @import '../../../public/css/main.css';
</style>

<style scoped>
.dashboard {
    display: flex;

    & .dashboard-content{
        display: flex;
        align-items: center;
        margin: 7rem 0;
        width: 100%;
        flex-direction: column;

        & .Cards-container {
            display: flex;
            margin: 3rem 0;
            width: 90vw;
            justify-content: space-evenly;
        }
    }

    @media (max-width: 1600px) {
        & .dashboard-content{
            & .Cards-container {
                width: 80vw;
            }
        }
    }

    @media (max-width: 1300px) {
        & .dashboard-content{
            & .Cards-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                width: 74vw;
            }
        }

            .cards{
                flex-direction: column;
                align-items: center;
                gap: 0;
            }
    }

        @media (max-width: 600px) {
            & .dashboard-content{
            & .Cards-container {
                display: grid;
                grid-template-columns: 1fr;
            }
        }
    }
}

</style>