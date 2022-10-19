import 'reflect-metadata';

import {container} from 'tsyringe';
import DependencyContainer from 'tsyringe/dist/typings/types/dependency-container';
import {DIModules} from './data/di/DIModules';

export function createDIContainer(): DependencyContainer {
  for (const module of DIModules) {
    new module().register(container);
  }
  return container;
}
