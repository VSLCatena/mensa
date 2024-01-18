import { Module } from '@nestjs/common';
import { databaseProviders } from './common/providers/database.provider';
import { ConfigModule } from '@nestjs/config';

@Module({
	imports: [ConfigModule.forRoot()],
	providers: [...databaseProviders]
})
export class AppModule {}
