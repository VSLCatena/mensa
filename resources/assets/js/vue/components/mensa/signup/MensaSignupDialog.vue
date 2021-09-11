<template>
    <v-dialog max-width="800" v-model="isOpen" transition="dialog-bottom-transition">
        <v-card outlined v-if="mensa != null">
            <v-card-title>{{ $ll($lang.text.signup.mensa_at) }} {{ formattedDate }}</v-card-title>
            <v-tabs v-model="tab" show-arrows>
                <v-tabs-slider></v-tabs-slider>
                <v-tab key="0">{{ $ll($lang.text.signup.tab_signup)}}</v-tab>
                <v-tab v-for="i in intros.length" :key="i + 1">
                    {{ $ll($lang.text.signup.tab_intro)}}<span v-if="intros.length > 1">&nbsp;#{{ i }}</span>
                </v-tab>
                <v-tab @click="addIntro()" key="1">{{ $ll($lang.text.signup.add_intro) }}</v-tab>
            </v-tabs>
            <v-divider></v-divider>
            <v-tabs-items v-model="tab">
                <v-tab-item key="0" class="pa-5">
                    <v-text-field
                        v-model="email"
                        :label="$ll($lang.text.signup.field_email)"
                    ></v-text-field>
                    <MensaSignupEntry :signup="signup" />
                </v-tab-item>
                <v-tab-item v-for="(intro, index) in intros" :key="index + 2">
                    <MensaSignupEntry :signup="intro" />
                </v-tab-item>
                <v-tab-item key="1"></v-tab-item>
            </v-tabs-items>
            <v-card-actions class="justify-end">
                <v-btn text @click="isOpen = false">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script lang="ts">
    import Vue from 'vue';
    import Mensa from "../../../../domain/mensa/model/Mensa";
    import {formatDate} from "../../../formatters/DateFormatter";
    import {createEmptySignup, NewMensaSignup} from "../../../../domain/mensa/model/MensaSignup";
    import { UserEmail } from "../../../../domain/common/model/User";
    import MensaSignupEntry from "./MensaSignupEntry.vue";

    export default Vue.extend({
        components: {MensaSignupEntry},
        data: function() {
            let potentialUser = this.$user;
            let user = { email: "" } as UserEmail;
            if ('email' in potentialUser) {
                user = potentialUser as UserEmail;
            }

            return {
                isOpen: false,
                mensa: null as Mensa|null,
                email: this.$user.email ?? '',
                tab: 0,
                signup: null as NewMensaSignup|null,
                intros: [] as NewMensaSignup[],
                user: user
            }
        },
        watch: {
            intros: function(after: [], before: []) {
                if (before.length < after.length) {
                    this.tab = after.length + 1;
                }
            }
        },
        methods: {
            open: function (mensa: Mensa) {
                if (this.mensa != mensa) {
                    this.signup = createEmptySignup(mensa.id, this.$user);
                    this.intros = [];
                }

                this.mensa = mensa;
                this.isOpen = true;
            },
            addIntro: function () {
                let mensa = this.mensa;
                if (mensa == null) return;

                this.intros = [...this.intros, createEmptySignup(mensa.id, this.$user, true)];
            }
        },
        computed: {
            formattedDate: function(): string | null {
                let mensa = this.mensa;
                if (mensa == null) return null;

                return formatDate(mensa.date);
            },
        }
    });
</script>
