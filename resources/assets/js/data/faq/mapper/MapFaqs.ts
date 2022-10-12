import MensaListEntity from "../../mensa/model/MensaListEntity";
import Result, {runCatching} from "../../../domain/common/utils/Result";
import {
    checkIsArray,
    requireNumberProp,
    requireStringProp
} from "../../utils/MappingUtils";
import Faq from "../../../domain/faq/model/Faq";
import FaqEntity from "../model/FaqEntity";

export default function MapFaqs(data: FaqEntity[]): Result<Faq[]> {
    return runCatching(() => {
        checkIsArray('faqs', data);
        return data.map((faq) => {
            return MapFaq(faq).getOrThrow()
        });
    });
}

function MapFaq(data: FaqEntity): Result<Faq> {
    return runCatching(() => {
       return {
           id: requireStringProp(data, 'id'),
           question: requireStringProp(data, 'question'),
           answer: requireStringProp(data, 'answer')
       }
    });
}