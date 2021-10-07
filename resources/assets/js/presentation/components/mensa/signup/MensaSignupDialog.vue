<template>
    <v-dialog max-width="800" v-model="isOpen" transition="dialog-bottom-transition" :persistent="loading">
        <v-card outlined v-if="mensa != null">
            <v-toolbar>
                <v-card-title>{{ $ll($lang.text.signup.mensa_at) }} {{ formattedDate }}</v-card-title>
            </v-toolbar>
            <v-form ref="signupForm" v-if="step === 1">
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
                        <MensaSignupEntry :mensa="mensa" :signup="signup" :enabled="!loading" />
                    </v-tab-item>
                    <v-tab-item v-for="(intro, index) in intros" :key="index + 1" class="pa-5">
                        <MensaSignupEntry :mensa="mensa" :signup="intro" :enabled="!loading" />
                    </v-tab-item>
                </v-tabs-items>
            </v-form>
            <v-form ref="emailForm" v-if="step === 2">
                <v-text-field
                    :label="$ll($lang.text.signup.field_email)"
                    :disabled="loading"
                    v-model="email"
                    :rules="validation.email"
                    hide-details="auto"
                    class="pa-5" />
            </v-form>

            <v-card-actions>
                <v-btn text :loading="loading" @click="addIntro()" v-if="(intros.length < 1 || $local.user.isAdmin) && step === 1">{{ $ll($lang.text.signup.add_intro) }}</v-btn>
                <v-btn text :loading="loading" @click="deleteIntro()" v-if="tab !== 0 && step === 1">{{ $ll($lang.text.signup.remove_intro) }}</v-btn>
                <v-btn text @click="step = 1" v-if="step === 2">{{ $ll($lang.text.general.previous) }}</v-btn>
                <v-spacer></v-spacer>
                <v-btn text @click="toEmail()" v-if="step === 1">{{ $ll($lang.text.general.next) }}</v-btn>
                <v-btn text :loading="loading" @click="sendSignup()" v-if="step === 2">{{ $ll($lang.text.signup.button_signup) }}</v-btn>
                <v-btn text :loading="loading" @click="isOpen = false">{{ $ll($lang.text.general.close) }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script lang="ts">
    import Vue from 'vue';
    import Mensa from "../../../../domain/mensa/model/Mensa";
    import {formatDate} from "../../../formatters/DateFormatter";
    import MensaSignup from "../../../../domain/mensa/model/MensaSignup";
    import MensaSignupEntry from "./MensaSignupEntry.vue";
    import {Validations} from "../../../utils/ValidationRules";
    import {User} from "../../../../domain/common/model/User";
    import SignupMensa from "../../../../domain/mensa/usecase/SignupMensa";

    export default Vue.extend({
        components: {MensaSignupEntry},
        data: function() {

            return {
                isOpen: false,
                mensa: null as Mensa|null,
                email: "",
                step: 1,
                tab: 0,
                signup: null as Partial<MensaSignup>|null,
                intros: [] as Partial<MensaSignup>[],
                validation: {
                    email: Validations.email,
                },
                loading: false
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
                    this.clearDialog(mensa);
                }

                this.mensa = mensa;
                this.isOpen = true;
                this.loading = false;
                this.tab = 0;
                this.step = 1;

                this.email = this.$local.user?.email ?? "";
            },
            clearDialog: function(mensa: Mensa) {
                this.signup = this.createEmptySignup(mensa, this.$local.user);
                this.intros = [];
                this.tab = 0;
                this.step = 1;
            },
            addIntro: function () {
                let mensa = this.mensa;
                if (mensa == null) return;

                this.intros = [...this.intros, this.createEmptySignup(mensa, this.$local.user, true)];
            },
            deleteIntro: function () {
                let intros = [...this.intros];
                intros.splice(this.tab - 1, 1);
                this.intros = intros;
                this.tab -= 1;
            },
            toEmail: function() {
                if (this.$refs.signupForm.validate()) {
                    this.step = 2;
                }
            },
            sendSignup: function() {
                if (!this.$refs.emailForm.validate()) {
                    return;
                }
                if (this.mensa == null || this.signup == null) return;

                this.loading = true;
                SignupMensa(this.mensa, this.email, [this.signup, ...this.intros])
                    .then(() => {
                        this.loading = false;
                        this.isOpen = false;
                        let mensa = this.mensa;
                        if (mensa != null) this.clearDialog(mensa);
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
            createEmptySignup: function(
                mensa: Mensa,
                user: User,
                isIntro: boolean = false
            ): MensaSignup {
                let signup: MensaSignup = {
                    foodOption: null,
                    isIntro: isIntro,
                    allergies: "",
                    extraInfo: "",
                }

                if (!isIntro) {
                    if ('foodPreference' in user && user.foodPreference != null
                        && mensa.foodOptions.indexOf(user.foodPreference) != -1) {
                        signup.foodOption = user.foodPreference;
                    }

                    signup.extraInfo = ('extraInfo' in user ? user.extraInfo : "") ?? "";
                    signup.allergies = ('allergies' in user ? user.allergies : "") ?? "";
                    signup.cook = false;
                    signup.dishwasher = false;
                }

                if (mensa.foodOptions.length == 1) {
                    signup.foodOption = mensa.foodOptions[0];
                }

                return signup;
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
