<template>
    <v-app id="app">
        <UserDialog ref="userDialog"/>
        <v-app-bar app>
            <v-toolbar-title class="me-3">{{ appName }}</v-toolbar-title>
            <v-toolbar-items>
                <v-btn to="/" text>Home</v-btn>
                <v-btn to="/mensa" text>Mensa</v-btn>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-icon large class="mx-4" @click="openLogin()">mdi-account</v-icon>
            <v-divider vertical></v-divider>
            <v-menu :close-on-content-click="false">
                <template v-slot:activator="{ on, attrs }">
                    <v-icon large class="ml-4" v-bind="attrs" v-on="on">mdi-cog</v-icon>
                </template>
                <v-list>
                    <v-list-item @click="toggleDarkMode()" selectable>
                        <v-list-item-icon>
                            <v-icon>{{ isDarkMode ? 'mdi-brightness-3' : 'mdi-brightness-7' }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            {{
                                $ll(isDarkMode ? $lang.text.menu.switch_theme.to_light : $lang.text.menu.switch_theme.to_dark)
                            }}
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item @click="toggleLanguage()" selectable>
                        <v-list-item-icon>
                            <img class="lang-flag" :src="enimage" v-if="currentLanguage !== 'en'"/>
                            <img class="lang-flag" :src="nlimage" v-if="currentLanguage !== 'nl'"/>
                        </v-list-item-icon>
                        <v-list-item-content>
                            {{ $ll($lang.text.menu.switch_language) }}
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-main>
            <v-container class="col-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8 col-xl-offset-3 col-xl-6">
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script lang="ts">
    import Vue from "vue";
    import Config from "../Config"
    import Language from "../domain/common/model/Language";
    import SetDarkMode from "../domain/storage/usecase/SetDarkMode";
    import SetLanguage from "../domain/storage/usecase/SetLanguage";
    import UserDialog from "./components/user/UserDialog.vue";

    export default Vue.extend({
        components: {UserDialog},
        props: {
            nlimage: String,
            enimage: String
        },
        data: function () {
            return {}
        },
        methods: {
            toggleDarkMode: function () {
                let newDarkMode = !this.$vuetify.theme.dark
                this.$vuetify.theme.dark = newDarkMode;
                SetDarkMode(newDarkMode);
            },
            toggleLanguage: function () {
                let newLanguage = new Language(this.$local.language.language == "nl" ? "en" : "nl");
                this.$local.language = newLanguage;
                SetLanguage(newLanguage);
            },
            openLogin: function () {
                (this.$refs.userDialog as any).open();
            },
        },
        computed: {
            isDarkMode: function (): boolean {
                return this.$vuetify.theme.dark;
            },
            currentLanguage: function (): string {
                return this.$local.language.language;
            },
            appName: function (): string | undefined {
                return Config.APP_NAME;
            }
        }
    });
</script>

<style lang="css">
    .lang-flag {
        width: 24px;
        height: 24px;
    }
</style>