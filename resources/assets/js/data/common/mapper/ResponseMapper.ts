import {AxiosResponse} from 'axios';
import {injectable} from 'tsyringe';

@injectable()
export class ResponseMapper {
  map<T = unknown, R = AxiosResponse<T>>(response: R): T {
    return (response as unknown as AxiosResponse<T>).data;
  }
}
