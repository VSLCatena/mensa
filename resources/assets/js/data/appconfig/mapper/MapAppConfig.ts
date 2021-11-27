import Result, {runCatching} from "../../../domain/common/utils/Result";
import {requireNotNull} from "../../utils/MappingUtils";
import AppConfigEntity, {DefaultMensaOptionsEntity} from "../model/AppConfigEntity";
import AppConfig, {DefaultMensaOptions} from "../../../domain/appconfig/model/AppConfig";

export default function MapAppConfig(data: AppConfigEntity): Result<AppConfig> {
    return runCatching(() => {
        let mensa = MapDefaultMensaOptions(requireNotNull('mensa', data.defaultMensaOptions)).getOrThrow()
        return {
            defaultMensaOptions: mensa
        }
    });
}

function MapDefaultMensaOptions(data: DefaultMensaOptionsEntity): Result<DefaultMensaOptions> {
    return runCatching(() => {
        return {
            title: requireNotNull('title', data.title),
            price: requireNotNull('price', data.price),
            maxSignups: requireNotNull('maxSignups', data.maxSignups)
        }
    })
}