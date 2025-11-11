<template>
  <div class="min-h-[100dvh] bg-background text-foreground">
    <!-- Navigation mit Logo + Darkmode-Toggle -->
    <NavBar :homeHref="routes.home" :links="[]" :showMenu="false" :showCtas="false" />

    <main>
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-center min-h-[calc(100dvh-4rem)] py-12">
          <div class="w-full max-w-md">
            <div class="relative rounded-2xl border border-border/60 bg-card/80 backdrop-blur-sm shadow-xl">
              <div class="p-6 md:p-8">
                <!-- Branding -->
                <div class="flex items-center gap-3 mb-3">
                  <div class="size-9 rounded-xl bg-primary/90 text-primary-foreground grid place-items-center font-bold shadow-sm">
                    A
                  </div>
                  <div>
                    <h1 class="text-xl md:text-2xl font-semibold leading-tight">Admin Login</h1>
                    <p class="text-sm text-muted-foreground mt-1">
                      Bitte melde dich mit deinen Zugangsdaten an.
                    </p>
                  </div>
                </div>

                <!-- Fehler -->
                <div v-if="error" class="mt-3 rounded-lg border border-destructive/40 bg-destructive/10 text-destructive px-3 py-2 text-sm">
                  {{ error }}
                </div>

                <!-- Formular -->
                <form :action="routes.login" method="post" class="mt-4 space-y-5" novalidate>
                  <!-- E-Mail -->
                  <div class="space-y-2">
                    <label for="username" class="text-sm font-medium">E-Mail</label>
                    <input
                      class="block w-full rounded-xl border border-border bg-background/60 dark:bg-background/40 px-4 py-3 text-foreground
                             placeholder:text-muted-foreground outline-none focus-visible:ring-2 focus-visible:ring-primary
                             focus-visible:border-primary transition"
                      type="email"
                      id="username"
                      name="_username"
                      :value="lastUsername"
                      placeholder="name@domain.tld"
                      autocomplete="username"
                      required
                    />
                    <p class="text-xs text-muted-foreground">Deine Admin-E-Mail-Adresse.</p>
                  </div>

                  <!-- Passwort -->
                  <div class="space-y-2">
                    <label for="password" class="text-sm font-medium">Passwort</label>
                    <input
                      class="block w-full rounded-xl border border-border bg-background/60 dark:bg-background/40 px-4 py-3 text-foreground
                             placeholder:text-muted-foreground outline-none focus-visible:ring-2 focus-visible:ring-primary
                             focus-visible:border-primary transition"
                      type="password"
                      id="password"
                      name="_password"
                      placeholder="••••••••"
                      autocomplete="current-password"
                      required
                    />
                  </div>

                  <!-- Remember + Passwort vergessen -->
                  <div class="flex items-center justify-between gap-3 pt-1">
                    <label class="inline-flex items-center gap-2 text-sm text-muted-foreground select-none">
                      <input type="checkbox" name="_remember_me" value="on"
                             class="size-4 rounded border-border text-primary focus:ring-primary" />
                      Angemeldet bleiben
                    </label>

                    <a class="text-sm font-medium text-primary hover:underline" :href="routes.forgot">
                      Passwort vergessen?
                    </a>
                  </div>

                  <!-- CSRF -->
                  <input type="hidden" name="_csrf_token" :value="csrf" />

                  <!-- Submit -->
                  <button type="submit"
                          class="inline-flex w-full items-center justify-center rounded-xl bg-primary text-primary-foreground font-semibold
                                 px-4 py-3 shadow-sm hover:brightness-105 active:translate-y-[1px] transition">
                    Login
                  </button>

                  <p class="text-center text-sm text-muted-foreground">Nur für berechtigte Administratoren.</p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import NavBar from '@/components/NavBar.vue';

const props = defineProps({
  routes: { type: Object, required: true }, // { home, login, forgot }
  csrf: { type: String, required: true },
  lastUsername: { type: String, default: null },
  error: { type: String, default: null },
});
</script>
