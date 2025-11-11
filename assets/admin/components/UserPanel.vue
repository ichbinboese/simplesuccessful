<template>
  <section class="card space-y-6">
    <header class="flex items-start justify-between gap-4">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Benutzer</p>
        <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Admins & Rollen</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Übersicht aller Backoffice-Konten. Entferne alte Zugänge mit einem Klick.
        </p>
      </div>
      <button type="button" class="btn btn-ghost text-sm" @click="$emit('close')">
        Schließen
      </button>
    </header>

    <div v-if="loading" class="space-y-3">
      <div v-for="n in 3" :key="n" class="h-16 animate-pulse rounded-2xl bg-slate-100 dark:bg-slate-800/60"></div>
    </div>

    <ul v-else class="space-y-3">
      <li
        v-for="user in users"
        :key="user.id"
        class="rounded-2xl border border-slate-200/80 bg-white/90 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900/60 flex flex-col gap-3 md:flex-row md:items-center md:justify-between"
      >
        <div>
          <p class="text-base font-semibold text-slate-900 dark:text-white">{{ user.email }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Rollen: {{ user.roles.join(', ') }}</p>
        </div>
        <button
          type="button"
          class="btn btn-secondary text-xs"
          :disabled="deletingId === user.id"
          @click="deleteUser(user)"
        >
          {{ deletingId === user.id ? 'Lösche…' : 'Löschen' }}
        </button>
      </li>
    </ul>

    <p v-if="users.length === 0 && !loading" class="text-sm text-slate-500 dark:text-slate-400">
      Noch keine Benutzer vorhanden.
    </p>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['close', 'changed']);

const users = ref([]);
const loading = ref(false);
const deletingId = ref(null);

const loadUsers = () => {
  loading.value = true;
  axios
    .get('/api/admin/user/list')
    .then(({ data }) => {
      users.value = data.items || [];
    })
    .finally(() => {
      loading.value = false;
    });
};

const deleteUser = (user) => {
  if (deletingId.value || !confirm(`Benutzer ${user.email} wirklich löschen?`)) {
    return;
  }
  deletingId.value = user.id;
  axios
    .delete(`/api/admin/user/${user.id}`)
    .then(() => {
      users.value = users.value.filter((u) => u.id !== user.id);
      emit('changed');
    })
    .catch((error) => {
      alert(error.response?.data?.message || 'Löschen fehlgeschlagen.');
    })
    .finally(() => {
      deletingId.value = null;
    });
};

onMounted(loadUsers);
</script>
