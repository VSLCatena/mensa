import AuthorizationUriEntity from "../model/AuthorizationUriEntity";
import Result, {Failure, Success} from "../../../domain/common/utils/Result";

export default function MapAuthorizationUri(entity: AuthorizationUriEntity|null): Result<string> {
    if (entity == null || entity.authorizationUri == null) {
        return new Failure(Error("Empty authorization uri"));
    } else {
        return new Success(entity.authorizationUri);
    }
}