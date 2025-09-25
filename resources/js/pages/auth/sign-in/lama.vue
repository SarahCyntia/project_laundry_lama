<template>
  <div class="login-container">
    <div class="login-box">
      <!-- Bagian kiri -->
      <div class="login-left">
        <!-- <img src="/img/admin.png" alt="illustration" class="illustration" /> -->
         <img src="/storage/image/admin.png" class="orang" />
        <h2>SLaundry</h2>
        <p>Rapi, Bersih, Wangi</p>
      </div>

      <!-- Bagian kanan -->
      <div class="login-right">
        <!-- <img src="/img/logo.png" alt="logo" class="logo" /> -->
        <h3>Login Admin</h3>
        <p>Masuk untuk mengelola SLaundry</p>

        <input type="email" placeholder="Email" />
        <input type="password" placeholder="Password" />
<!-- 
        <div class="remember">
          <input type="checkbox" id="ingat" />
          <label for="ingat">Ingat saya</label>
        </div> -->

        <button>➜ Masuk</button>

        <p class="alt-login">
          Bukan admin? <a href="#">ke halaman pelanggan →</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { getAssetPath } from "@/core/helpers/assets";
import { defineComponent, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import * as Yup from "yup";
import axios from "@/libs/axios";
import { toast } from "vue3-toastify";
import { blockBtn, unblockBtn } from "@/libs/utils";

import WithEmail from "./tabs/WithEmail.vue";
import WithPhone from "./tabs/WithPhone.vue";
import { useSetting } from "@/services";

export default defineComponent({
    name: "sign-in",
    components: {
        WithEmail,
        WithPhone,
    },
    setup() {
        const store = useAuthStore();
        const router = useRouter();
        const { data: setting = {} } = useSetting();

        const submitButton = ref(null);

        //Create form validation object
        const login = Yup.object().shape({
            identifier: Yup.string()
                .email("Email/No. Telepon tidak valid")
                .required("Harap masukkan Email/No. Telepon")
                .label("Email"),
            password: Yup.string()
                .min(8, "Password minimal terdiri dari 8 karakter")
                .required("Harap masukkan password")
                .label("Password"),
        });

        return {
            login,
            submitButton,
            getAssetPath,
            store,
            router,
            setting,
        };
    },
    data() {
        return {
            data: {
                identifier: null,
                password: null,
            },
            check: {
                type: "",
                error: "",
            },
        };
    },
    methods: {
        submit() {
            blockBtn(this.submitButton);

            axios
                .post("/auth/login", { ...this.data, type: this.check.type })
                .then((res) => {
                    this.store.setAuth(res.data.user, res.data.token);
                    this.router.push("/dashboard");
                })
                .catch((error) => {
                    toast.error(error.response.data.message);
                })
                .finally(() => {
                    unblockBtn(this.submitButton);
                });
        },
        checkInput(value) {
            this.check.type = "";
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                this.check.type = "email";
            } else {
                this.check.type = "phone";
                if (isNaN(this.data.identifier)) {
                    this.check.type =
                        "Masukkan Email / No. Telepon Yang Valid!";
                }
            }
        },
    },
});
</script>


<style scoped>
/* .login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: #f8f9fc;
} */

.login-box {
  display: flex;
  width: 900px;
  min-height: 500px;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  overflow: hidden;
}

.login-left {
  flex: 1;
  background: #2ac1c4;
  color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px;
  text-align: center;
}

.login-left .illustration {
  width: 150px;
  margin-bottom: 20px;
}

.login-left h2 {
  font-size: 26px;
  margin-bottom: 8px;
}

.login-left p {
  font-size: 16px;
  opacity: 0.9;
}

.login-right {
  flex: 1;
  padding: 50px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.login-right .logo {
  width: 100px;
  margin: 0 auto 20px;
  display: block;
}

.login-right h3 {
  font-size: 22px;
  color: #00a5a8;
  text-align: center;
  margin-bottom: 10px;
}

.login-right p {
  text-align: center;
  font-size: 14px;
  margin-bottom: 25px;
  color: #666;
}

.login-right input {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border-radius: 30px;
  border: 1px solid #ddd;
  font-size: 15px;
  outline: none;
}

.login-right input:focus {
  border-color: #2ac1c4;
}

.remember {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
  font-size: 14px;
  color: #555;
}

.login-right button {
  width: 100%;
  padding: 12px;
  border-radius: 30px;
  border: none;
  background: #2ac1c4;
  color: #fff;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

.login-right button:hover {
  background: #238f91;
}

.alt-login {
  margin-top: 20px;
  text-align: center;
  font-size: 14px;
}

.alt-login a {
  color: #f5a623;
  font-weight: bold;
  text-decoration: none;
}
</style>