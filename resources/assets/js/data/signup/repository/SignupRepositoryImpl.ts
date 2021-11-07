import Mensa from '../../../domain/mensa/model/Mensa';
import MensaSignup from '../../../domain/signup/model/MensaSignup';
import Config from '../../../Config';
import axios from "axios";
import {OptionalAuthHeader} from "../../utils/WithAuthHeader";
import {SignupRepository} from "../../../domain/signup/repository/SignupRepository";
import MapResponse from "../../utils/MapResponse";
import {MapSignup} from "../../mensa/mapper/MapSignups";

class MensaRepositoryImpl implements SignupRepository {
    getSignup(
        mensaId: string,
        authToken: string | null
    ): Promise<MensaSignup> {
        return axios.get(`${Config.API_BASE_URL}/mensa/${mensaId}/signup`, {
            headers: OptionalAuthHeader(authToken)
        })
            .then(MapResponse)
            .then(value => MapSignup(value).asPromise());
    }

    signup(
        mensa: Mensa,
        email: string,
        signups: MensaSignup[],
        authToken: string | null
    ): Promise<void> {
        return axios.post(`${Config.API_BASE_URL}/mensa/${mensa.id}/signup`, {
            email: email,
            signups: signups,
        }, {headers: OptionalAuthHeader(authToken)})
            .then(MapResponse);
    }

    editSignup(
        mensa: Mensa,
        email: string,
        signups: MensaSignup[],
        authToken: string | null
    ): Promise<void> {
        return axios.put(`${Config.API_BASE_URL}/mensa/${mensa.id}/signup`, {
            email: email,
            signups: signups
        }, {headers: OptionalAuthHeader(authToken)})
            .then(MapResponse);
    }

    signout(
        mensaId: string,
        signupId: string,
        confirmationCode: string,
        authToken: string | null
    ): Promise<void> {
        return axios.delete(`${Config.API_BASE_URL}/mensa/${mensaId}/signup/${signupId}`, {
            headers: OptionalAuthHeader(authToken),
            data: {confirmation_code: confirmationCode}
        }).then(MapResponse);
    }
}

const repository = new MensaRepositoryImpl();
export default repository;