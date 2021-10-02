import Result, {Failure, Success} from "../../../domain/common/utils/Result";
import TokenEntity from "../model/TokenEntity";

export default function MapToken(entity: TokenEntity|null): Result<string> {
    if (entity == null || entity.token == null) {
        return new Failure(Error("Empty token"));
    } else {
        return new Success(entity.token);
    }
}