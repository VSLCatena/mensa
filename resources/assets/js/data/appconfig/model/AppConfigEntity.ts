export default interface AppConfigEntity {
    defaultMensaOptions?: DefaultMensaOptionsEntity
}

export interface DefaultMensaOptionsEntity {
    title?: string,
    price?: number,
    maxSignups?: number
}