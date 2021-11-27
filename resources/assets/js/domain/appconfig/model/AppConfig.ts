export default interface AppConfig {
    defaultMensaOptions: DefaultMensaOptions
}

export interface DefaultMensaOptions {
    title: string,
    price: number,
    maxSignups: number
}