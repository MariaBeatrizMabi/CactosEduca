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
            <TitleComponent title="Análise Geral das escolas"/>
            <div class="searcheble">
                <input class="seacheble-camp" placeholder="Digite o nome do múnicipio">
                <a class="send-searche">
                    <svg width="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
                    Pesquisar
                </a>
            </div>
            <div class="tableContent">
   <div class="table-container">
    <div class="titleTable">
        <h1>Escolas cadastrados - turma 01 | Ensino fundamental</h1>
    </div>
    <table>
        <tr>
            <th>Nome</th>
            <th>Sexo</th>   
            <th>Matrícula</th>
            <th>Data de matrícula</th>
            <th>Data de nascimento</th>
            <th>Etapa</th>
        </tr>
        <tr>
            <td> ANNY BEATRYZ SANTOS </td>
            <td> F </td>
            <td> 3113 </td>
            <td> 04/01/2024 </td>
            <td> 13/09/2017 </td>
            <td> 1° ano </td>
        </tr>
        <tr>
            <td> GABRIEL SCHNEIDER COGO </td>
            <td> M </td>
            <td> 3114 </td>
            <td> 04/01/2024 </td>
            <td> 06/11/2017 </td>
            <td> 1° ano  </td>
        </tr>
        <tr>
            <td> JOÃO MIGUEL FRAGA DA SILVA </td>
            <td> M </td>
            <td> 3115 </td>
            <td> 04/01/2024 </td>
            <td> 15/08/2017 </td>
            <td> 1° ano </td>
        </tr>
        <tr>
            <td> LUIZ FERNANDO NUNES DA SILVA  </td>
            <td> M </td>
            <td> 3117 </td>
            <td> 04/01/2024 </td>
            <td> 29/04/2017 </td>
            <td> 1° ano  </td>
        </tr>
        <tr>
            <td> PAULO DANIEL DE SOUZA  </td>
            <td> M </td>
            <td> 3118 </td>
            <td> 04/01/2024 </td>
            <td> 23/10/2017 </td>
            <td> 1° ano  </td>
        </tr>
        <tr>
            <td> VANDENBERG ENZO DE SOUZA PEREIRA  </td>
            <td> M </td>
            <td> 3119 </td>
            <td> 04/01/2024 </td>
            <td> 02/06/2017 </td>
            <td> 1° ano  </td>
        </tr>
        <tr>
            <td> GABRIEL DA ROCHA BROCOLI </td>
            <td> M </td>
            <td> 3237 </td>
            <td> 08/02/2024 </td>
            <td> 13/11/2016 </td>
            <td> 1° ano  </td>
        </tr>
        <tr>
            <td> MARIA VITORIA DE ALENCAR SILVA </td>
            <td> F </td>
            <td> 3241 </td>
            <td> 14/02/2024 </td>
            <td> 21/10/2017 </td>
            <td> 1° ano  </td>
        </tr>
        <tr>
            <td> MIGUEL AMADEUS DA SILVA BOEING </td>
            <td> M </td>
            <td> 3313 </td>
            <td> 14/03/2024 </td>
            <td> 22/03/2018 </td>
            <td> 1° ano  </td>
        </tr>
    </table>
   </div>

    </div>
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

    & .dashboard-content{
        display: flex;
        align-items: center;
        margin: 7rem 0;
        width: 100%;
        flex-direction: column;
        
    }

    & .table-container {
        padding: 2rem 0 3rem 0;
    }
}

.searcheble {
        display: flex;
        align-items: center;
        gap: 5%;
        width: 85%;
        margin: 2rem 0 0 0;
        
        & .seacheble-camp{
            padding: 0.7rem 1rem;
            width: 80%;
            border: 2px solid var(--black-color);
            border-radius: 4rem;
            outline: 0;
            font-size: 14px;
        }

        & .send-searche {
            width: 15%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            border: 2px solid var(--black-color);
            text-align: center;
            border-radius: 4rem;
            background-color: var(--white-color);
            color: var(--black-color);
            padding: 0.6rem;
        }
    }

</style>