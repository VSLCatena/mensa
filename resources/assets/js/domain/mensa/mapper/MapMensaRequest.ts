import EditMensa from "../model/EditMensa";
import MensaRequestModel from "../../../data/mensa/model/MensaRequestModel";
import MapRequestDate from "../../../data/common/MapRequestDate";


export default function MapMensaRequest(editMensa: EditMensa): MensaRequestModel {
    return {
        ...editMensa,
        date: MapRequestDate(editMensa.date),
        closingTime: MapRequestDate(editMensa.closingTime)
    }
}