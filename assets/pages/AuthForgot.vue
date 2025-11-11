<template>
  <div class="min-h-[100dvh] bg-background text-foreground">
    <NavBar :homeHref="routes.home" :links="[]" :showMenu="false" :showCtas="false" />
    <main>
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-center min-h-[calc(100dvh-4rem)] py-12">
          <div class="w-full max-w-md">
            <div class="relative rounded-2xl border border-border/60 bg-card/80 backdrop-blur-sm shadow-xl">
              <div class="p-6 md:p-8">
                <div class="flex items-center gap-3 mb-3">
                  <div class="size-9 rounded-xl bg-primary/90 text-primary-foreground grid place-items-center font-bold shadow-sm">A</div>
                  <div>
                    <h1 class="text-xl md:text-2xl font-semibold leading-tight">Passwort zurücksetzen</h1>
                    <p class="text-sm text-muted-foreground mt-1">Wir senden dir (falls ein Konto existiert) einen Link zum Zurücksetzen.</p>
                  </div>
                </div>

                <div v-for="msg in errorMessages" :key="msg" class="mt-3 rounded-lg border border-destructive/40 bg-destructive/10 text-destructive px-3 py-2 text-sm">
                  {{ msg }}
                </div>

                <form :action="routes.forgot" method="post" class="mt-4 space-y-5" novalidate>
                  <div class="space-y-2">
                    <label for="email" class="text-sm font-medium">E-Mail</label>
                    <input class="block w-full rounded-xl border border-border bg-background/60 dark:bg-background/40 px-4 py-3 text-foreground placeholder:text-muted-foreground outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:border-primary transition"
                      type="email" id="email" name="email" placeholder="name@domain.tld" autocomplete="email" inputmode="email" required />
                    <p class="text-xs text-muted-foreground">Nutze die E-Mail deines Admin-Kontos.</p>
                  </div>

                  <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-primary text-primary-foreground font-semibold px-4 py-3 shadow-sm hover:brightness-105 active:translate-y-[1px] transition">
                    Link anfordern
                  </button>

                  <div class="text-center">
                    <a class="text-sm font-medium text-primary hover:underline" :href="routes.login">Zurück zum Login</a>
                  </div>
                </form>
              </div>
            </div>
            <p class="mt-4 text-center text-sm text-muted-foreground">Nur für berechtigte Administratoren.</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import NavBar from '@/components/NavBar.vue';
const props = defineProps({
  routes: { type: Object, required: true },
  errorMessages: { type: Array, default: () => [] },
});
</script>
