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
   <div class="table-container" style="overflow-x: auto;">
    <div class="titleTable">
        <h1>Escolas cadastrados - turma 01 | Ensino fundamental</h1>
    </div>
    <table>
        <tr>
            <th>Nome</th>
            <th>Matrícula</th>
            <th class="registration">Data de matrícula</th>
            <th>Última Nota - Leitura</th>
            <th>Última Nota - Escrita</th>
            <th class="stage">Etapa</th>
        </tr>
        <tr>
            <td> ANNY BEATRYZ SANTOS </td>
            <td> 3113 </td>
            <td class="registration"> 04/01/2024 </td>
            <td> leitor de silabas </td>
            <td> pré-silábico </td>
            <td class="stage"> 1° ano </td>
        </tr>
        <tr>
            <td> GABRIEL SCHNEIDER COGO </td>
            <td> 3114 </td>
            <td class="registration"> 04/01/2024 </td>
            <td> leitor de palavras </td>
            <td> silábico </td>
            <td class="stage"> 1° ano  </td>
        </tr>
        <tr>
            <td> JOÃO MIGUEL FRAGA DA SILVA </td>
            <td> 3115 </td>
            <td class="registration"> 04/01/2024 </td>
            <td> não leitor </td>
            <td> Pré-silábico </td>
            <td class="stage"> 1° ano </td>
        </tr>
        <tr>
            <td> LUIZ FERNANDO NUNES DA SILVA  </td>
            <td> 3117 </td>
            <td class="registration"> 04/01/2024 </td>
            <td> leitor de texto sem fluência </td>
            <td> alfabético </td>
            <td class="stage"> 1° ano  </td>
        </tr>
        <tr>
            <td> PAULO DANIEL DE SOUZA  </td>
            <td> 3118 </td>
            <td class="registration"> 04/01/2024 </td>
            <td> leitor de texto sem fluência </td>
            <td> silábico </td>
            <td class="stage"> 1° ano  </td>
        </tr>
        <tr>
            <td> VANDENBERG ENZO DE SOUZA PEREIRA  </td>
            <td> 3119 </td>
            <td class="registration"> 04/01/2024 </td>
            <td> leitor de texto com fluência </td>
            <td> ortográfico </td>
            <td class="stage"> 1° ano  </td>
        </tr>
        <tr>
            <td> GABRIEL DA ROCHA BROCOLI </td>
            <td> 3237 </td>
            <td class="registration"> 08/02/2024 </td>
            <td> leitor de texto sem fluência </td>
            <td> ortográfico </td>
            <td class="stage"> 1° ano  </td>
        </tr>
        <tr>
            <td> MARIA VITORIA DE ALENCAR SILVA </td>
            <td> 3241 </td>
            <td class="registration"> 14/02/2024 </td>
            <td> leitor de texto com fluência </td>
            <td> ortográfico </td>
            <td class="stage"> 1° ano  </td>
        </tr>
        <tr>
            <td> MIGUEL AMADEUS DA SILVA BOEING </td>
            <td> 3313 </td>
            <td class="registration"> 14/03/2024 </td>
            <td> não leitor </td>
            <td> pré-silábico </td>
            <td class="stage"> 1° ano  </td>
        </tr>
    </table>
   </div>

    </div>
            <UserWelcomeComponet class="welcome-component"></UserWelcomeComponet>
            <TitleComponent title="Análise geral média turmas - Leitura"/>
            <ChartBarBimReading titleGrapichCard="Nível turmas das escolas - Leitura" />
            <TitleComponent title="Análise geral média turmas - Escrita"/>
            <ChartBarBimWriting titleGrapichCard="Nível geral das turmas - Escrita" />
        </div>
    </div>
</template>

<style scoped>
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

@media (max-width: 1270px){
    .cards{
        flex-direction: column;
        align-items: center;
        gap: 0;
    }
}

@media (max-width: 900px) {
    .titleTable{
        font-size: 9px;
    }

    .stage, .registration {
        display: none;
    }
}


@media (max-width: 550px) {
        .searcheble {
            display: flex;
            flex-direction: column !important;
            margin: 2rem 0;
        & .seacheble-camp{
            width: 100% !important;
        }

        & .send-searche {
            width: 100%;
        }
    }
}
</style>