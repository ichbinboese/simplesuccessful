<template>
  <section class="card space-y-5">
    <header>
      <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Admin Zugang</p>
      <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Neuen Benutzer erstellen</h2>
      <p class="text-sm text-slate-500 dark:text-slate-400">
        Lege einen zusätzlichen Admin an, um Zugriffe zu teilen. Passwörter werden sicher gehasht gespeichert.
      </p>
    </header>

    <form class="space-y-4" @submit.prevent="submit">
      <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-300">E-Mail</label>
          <input type="email" v-model="form.email" class="input" placeholder="admin@example.com" required />
          <p v-if="errors.email" class="text-xs text-rose-500">{{ errors.email }}</p>
        </div>
        <div class="space-y-2">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Passwort</label>
          <input type="password" v-model="form.password" class="input" placeholder="mind. 6 Zeichen" required />
          <p v-if="errors.password" class="text-xs text-rose-500">{{ errors.password }}</p>
        </div>
      </div>

      <div class="space-y-2">
        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Rollen</label>
        <select v-model="form.role" class="input">
          <option value="ROLE_ADMIN">ROLE_ADMIN</option>
          <option value="ROLE_SUPER_ADMIN">ROLE_SUPER_ADMIN</option>
        </select>
      </div>

      <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn btn-primary" :disabled="submitting">
          {{ submitting ? 'Speichere…' : 'Admin anlegen' }}
        </button>
        <button type="button" class="btn btn-ghost text-slate-500" @click="reset" :disabled="submitting">
          Zurücksetzen
        </button>
      </div>

      <p v-if="successMessage" class="text-sm text-emerald-600">{{ successMessage }}</p>
      <p v-if="errors.global" class="text-sm text-rose-600">{{ errors.global }}</p>
    </form>
  </section>
</template>

<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['created']);

const form = reactive({
  email: '',
  password: '',
  role: 'ROLE_ADMIN',
});

const errors = reactive({});
const submitting = ref(false);
const successMessage = ref('');

function reset() {
  form.email = '';
  form.password = '';
  form.role = 'ROLE_ADMIN';
  clearErrors();
  successMessage.value = '';
}

function clearErrors() {
  for (const key of Object.keys(errors)) {
    delete errors[key];
  }
}

async function submit() {
  clearErrors();
  successMessage.value = '';

  if (!form.email || !form.password) {
    errors.global = 'Bitte fülle alle Felder aus.';
    return;
  }

  submitting.value = true;
  try {
    const payload = {
      email: form.email,
      password: form.password,
      roles: [form.role],
    };
    const { data } = await axios.post('/api/admin/user', payload);
    if (data?.ok) {
      successMessage.value = 'Admin erfolgreich angelegt.';
      emit('created');
      reset();
    }
  } catch (error) {
    if (error.response?.status === 422) {
      Object.assign(errors, error.response.data.errors || {});
    } else {
      errors.global = error.response?.data?.message || 'Speichern fehlgeschlagen.';
    }
  } finally {
    submitting.value = false;
  }
}
</script>
