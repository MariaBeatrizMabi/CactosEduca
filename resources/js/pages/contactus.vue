<script setup>
import MenuComponent from '../components/menu.vue'
import UserWelcome from '../components/userWelcome.vue'
import TitleComponent from '../components/title.vue'

</script>

<template>
  <UserWelcome />
    <div class="contact-section">
      <MenuComponent />
        <div class="contact-content">
          <TitleComponent title="Contate-nos"></TitleComponent>
          <div class="content">
            <div class="content-infos">
              <h2>Tem alguma dúvida? Fale com a nossa equipe através dos nossos meios de contato.</h2>
              
              <p>
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/>
                </svg>  Email: contato@editoracactos.com.br</p>
              <p>
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/>
                </svg>
                Telefone: (85) 99614-1000</p>

              <h3>Para mais informações, consulte o nosso website.</h3>
              <p>
                <svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <path d="M352 256c0 22.2-1.2 43.6-3.3 64H163.3c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64H348.7c2.2 20.4 3.3 41.8 3.3 64zm28.8-64H503.9c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64H380.8c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32H376.7c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 171.9 151.6zm-149.1 0H167.7c6.1-36.4 15.5-68.6 27-94.7c10.5-23.6 22.2-40.7 33.5-51.5C239.4 3.2 248.7 0 256 0s16.6 3.2 27.8 13.8c11.3 10.8 23 27.9 33.5 51.5c11.6 26 20.9 58.2 27 94.7zm-209 0H18.6C48.6 85.9 112.2 29.1 190.6 8.4C165.1 42.6 145.3 96.1 135.3 160zM8.1 192H131.2c-2.1 20.6-3.2 42-3.2 64s1.1 43.4 3.2 64H8.1C2.8 299.5 0 278.1 0 256s2.8-43.5 8.1-64zM194.7 446.6c-11.6-26-20.9-58.2-27-94.6H344.3c-6.1 36.4-15.5 68.6-27 94.6c-10.5 23.6-22.2 40.7-33.5 51.5C272.6 508.8 263.3 512 256 512s-16.6-3.2-27.8-13.8c-11.3-10.8-23-27.9-33.5-51.5zM135.3 352c10 63.9 29.8 117.4 55.3 151.6C112.2 482.9 48.6 426.1 18.6 352H135.3zm358.1 0c-30 74.1-93.6 130.9-171.9 151.6c25.5-34.2 45.2-87.7 55.3-151.6H493.4z"/>
                </svg>
                Acesse clicando aqui: <a href="https://editoracactos.com.br/">Cactos Educa</a></p>

              <h4>A Cactos editora agradeçe seu contato.  </h4>
            </div>
            <img src="/public/assets/AnimateGroupGif.gif">


          </div>
        </div>
    </div>
  </template>

<style scoped>
img {
  object-fit: contain;
}
.contact-section {
  display: flex;
  height: 100vh;
  background-color: rgba(255, 255, 255, 0.397);

  & .contact-content {
    display: flex;
    align-items: center;
    flex-direction: column;
    width: 100%;

    margin: 8rem 0;

    & .content {
      display: flex;
      width: 84%;
      margin: 1rem 0;
      & h2, h3, h4, a {
        color: var(--primary-color);
      }

      & a {
        font-weight: 600;
        font-size: 16px;
      }

      & .content-infos {
        display: flex;
        justify-content: center;
        flex-direction: column;
        gap: 2rem;
      }

      & p {
        font-size: 16px;
        color: var(--black-color);
        display: flex;
        gap: 5px;
        align-items: center;
      }
    }
  }

  @media (max-width: 1150px) {
        .content {
          display: flex;
          flex-direction: column;
        }

        .content > img {
          margin: 0 auto;
          width: 50vh;
        }
      }
}
</style>