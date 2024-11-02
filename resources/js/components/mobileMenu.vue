<script setup>
    const toggleMenu = () => {
        const nav = document.getElementById('menu');
        nav.classList.toggle('nav-active');

        const btnMobile = document.getElementById('nav');
        btnMobile.classList.toggle('active');
    };
</script>

<template>
    <header class="header-nav">
        <nav id="nav">
            <button id="btn-mobile" class="btn-mobile" @click="toggleMenu()">
                <span id="hamburger"></span></button>
            <ul id="menu" class="menu-mobile-itens">
                <li class="nav-link">
                    <a href="/dashboard">
                     Inicio
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/Filters">
                        Gráficos de desempenho
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/Management">
                       Escolas
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/ContactUs">
                        Contate-nos
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/">
                        Sair
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</template>

<style scoped>
.menu-mobile-itens > li {
    list-style: none;
}

.btn-mobile {
    display: block;
}

ul {
    display: flex;
}

#menu {
    display: block;
    position: absolute;
    width: 100%;
    top: 60px;
    right: 0px;
    background: var(--grap-color);
    transition: 0.6s;
    z-index: 1000;
    height: 0px;
    visibility: hidden;
    overflow-y: hidden;
    & li > a {
        padding: 2rem;
        border-bottom: 2px solid var(--secondary-color);
    }
}

#menu.nav-active{
    height: calc(100vh - 60px);
    visibility: visible;
    overflow-y: auto;
}
#btn-mobile {
    display: flex;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    color: var(--primary-color);
    border: none;
    background: none;
    cursor: pointer;
    gap: 0.5rem;
  }
#hamburger {
    border-top: 2px solid var(--primary-color);
    width: 20px;
  }
  #hamburger::after,
  #hamburger::before {
    content: '';
    display: block;
    width: 20px;
    height: 2px;
    color: var(--primary-color);
    background: var(--primary-color);
    margin-top: 5px;
    transition: 0.3s;
    position: relative;
  }
  #nav.active #hamburger {
    border-top-color: transparent;
  }
  #nav.active #hamburger::before {
    transform: rotate(135deg);
  }
  #nav.active #hamburger::after {
    transform: rotate(-135deg);
    top: -7px;
  }

</style>
