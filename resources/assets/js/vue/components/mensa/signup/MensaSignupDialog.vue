<template>
    <v-dialog max-width="800" v-model="isOpen" transition="dialog-bottom-transition">
        <v-card outlined v-if="mensa != null">
            <v-toolbar>
                <v-card-title>{{ $ll($lang.text.signup.mensa_at) }} {{ formattedDate }}</v-card-title>
            </v-toolbar>
            <div v-if="step === 1">
                <v-tabs v-model="tab" show-arrows>
                    <v-tabs-slider></v-tabs-slider>
                    <v-tab :key="0">{{ $ll($lang.text.signup.tab_signup)}}</v-tab>
                    <v-tab v-for="i in intros.length" :key="i">
                        {{ $ll($lang.text.signup.tab_intro)}}<span v-if="intros.length > 1">&nbsp;#{{ i }}</span>
                    </v-tab>
                </v-tabs>
                <v-divider></v-divider>

                <v-tabs-items v-model="tab">
                    <v-tab-item :key="0" class="pa-5">
                        <MensaSignupEntry :signup="signup" />
                    </v-tab-item>
                    <v-tab-item v-for="(intro, index) in intros" :key="index + 1" class="pa-5">
                        <MensaSignupEntry :signup="intro" />
                    </v-tab-item>
                </v-tabs-items>
            </div>
            <div v-if="step === 2">
                <v-text-field
                    :label="$ll($lang.text.signup.field_email)"
                    v-model="email"
                    :rules="validation.email"
                    hide-details="auto"
                    class="pa-5" />
            </div>

            <v-card-actions>
                <v-btn text @click="addIntro()" v-if="(intros.length < 1 || $user.isAdmin) && step === 1">{{ $ll($lang.text.signup.add_intro) }}</v-btn>
                <v-btn text @click="deleteIntro()" v-if="tab !== 0 && step === 1">{{ $ll($lang.text.signup.remove_intro) }}</v-btn>
                <v-btn text @click="step = 1" v-if="step === 2">{{ $ll($lang.text.general.previous) }}</v-btn>
                <v-spacer></v-spacer>
                <v-btn text @click="step = 2" v-if="step === 1">{{ $ll($lang.text.general.next) }}</v-btn>
                <v-btn text @click="isOpen = false">{{ $ll($lang.text.general.close) }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<!--<v-text-field-->
<!--    v-model="email"-->
<!--    :label="$ll($lang.text.signup.field_email)"-->
<!--    class="mx-5"-->
<!--&gt;</v-text-field>-->

<script lang="ts">
    import Vue from 'vue';
    import Mensa from "../../../../domain/mensa/model/Mensa";
    import {formatDate} from "../../../formatters/DateFormatter";
    import {createEmptySignup, NewMensaSignup} from "../../../../domain/mensa/model/MensaSignup";
    import { UserEmail } from "../../../../domain/common/model/User";
    import MensaSignupEntry from "./MensaSignupEntry.vue";
    import {Validations} from "../../../../utils/ValidationRules";

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
                email: user.email,
                step: 1,
                tab: 0,
                signup: null as NewMensaSignup|null,
                intros: [] as NewMensaSignup[],
                user: user,
                validation: {
                    email: Validations.email,
                }
            }
        },
        watch: {
            intros: function (after: [], before: []) {
                if (after.length > before.length) {
                    this.$nextTick(() => {
                        this.tab = this.intros.length;
                    });
                }
            }
        },
        methods: {
            open: function (mensa: Mensa) {
                if (this.mensa != mensa) {
                    this.signup = createEmptySignup(mensa.id, this.$user);
                    this.intros = [];
                    this.tab = 0;
                }

                this.mensa = mensa;
                this.isOpen = true;
            },
            addIntro: function () {
                let mensa = this.mensa;
                if (mensa == null) return;

                this.intros = [...this.intros, createEmptySignup(mensa.id, this.$user, true)];
            },
            deleteIntro: function () {
                this.tab -= 1;
                let intros = [...this.intros];
                intros.splice(this.tab - 1, 1);
                this.intros = intros;
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
