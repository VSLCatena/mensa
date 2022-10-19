import {Mensa} from '../model/Mensa';
import {MensaList} from '../model/MensaList';
import {EditMensa} from '../model/EditMensa';

export interface MensaRepository {
  getMensas: (
    weekOffset: number | null,
    authToken: string | null
  ) => Promise<MensaList>;

  getMensa: (mensaId: string) => Promise<Mensa>;

  addMensa: (mensa: EditMensa, authToken: string) => Promise<void>;

  editMensa: (mensa: EditMensa, authToken: string) => Promise<void>;

  deleteMensa: (mensaId: string, authToken: string) => Promise<void>;
}

export const MensaRepositoryToken = 'MensaRepositoryToken';
