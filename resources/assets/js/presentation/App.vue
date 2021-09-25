<template>
    <v-app id="app">
        <v-app-bar app>
            <v-toolbar-title class="me-3">{{ appName }}</v-toolbar-title>
            <v-toolbar-items>
                <v-btn to="/" text>Home</v-btn>
                <v-btn to="/mensa" text>Mensa</v-btn>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-icon large class="mx-4">mdi-account</v-icon>
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
                            {{ $ll(isDarkMode ? $lang.text.menu.switch_theme.to_light : $lang.text.menu.switch_theme.to_dark) }}
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item @click="toggleLanguage()" selectable>
                        <v-list-item-icon>
                            <img style="width: 24px; height: 24px" :src="nlimage" v-if="currentLanguage !== 'en'" />
                            <img style="width: 24px; height: 24px" :src="enimage" v-if="currentLanguage !== 'nl'" />
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
import {CurrentLanguage} from "./lang/Language";
import Config from "../Config"
import Language from "../domain/common/model/Language";
import SetDarkMode from "../domain/storage/usecase/SetDarkMode";
import SetLanguage from "../domain/storage/usecase/SetLanguage";

export default Vue.extend({
    props: {
        nlimage: String,
        enimage: String
    },
    methods: {
        toggleDarkMode: function() {
            let newDarkMode = !this.$vuetify.theme.dark
            this.$vuetify.theme.dark = newDarkMode;
            SetDarkMode(newDarkMode);
        },
        toggleLanguage: function() {
            let newLanguage = new Language(CurrentLanguage.language.language == "nl" ? "en": "nl");
            CurrentLanguage.language = newLanguage;
            SetLanguage(newLanguage);
        }
    },
    computed: {
        isDarkMode: function(): boolean {
            return this.$vuetify.theme.dark;
        },
        currentLanguage: function(): string {
            return this.$currentLanguage.language.language;
        },
        appName: function(): string|undefined {
            return Config.APP_NAME;
        }
    }
});
</script>