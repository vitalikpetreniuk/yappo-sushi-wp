<?php

namespace WPStaging\Pro\Backup\Job\Jobs;

use WPStaging\Backup\Job\Jobs\JobBackup as BasicJobBackup;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\BackupRequirementsCheckTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\AmazonS3StorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\DigitalOceanSpacesStorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\GenericS3StorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\GoogleDriveStorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\SftpStorageTask;
use WPStaging\Pro\Backup\Task\Tasks\JobBackup\RemoteStorageTasks\WasabiStorageTask;

class JobBackup extends BasicJobBackup
{
    protected function setRequirementTask()
    {
        $this->tasks[] = BackupRequirementsCheckTask::class;
    }

    protected function addStoragesTasks()
    {
        if ($this->jobDataDto->isUploadToGoogleDrive()) {
            $this->tasks[] = GoogleDriveStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToAmazonS3()) {
            $this->tasks[] = AmazonS3StorageTask::class;
        }

        if ($this->jobDataDto->isUploadToSftp()) {
            $this->tasks[] = SftpStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToDigitalOceanSpaces()) {
            $this->tasks[] = DigitalOceanSpacesStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToWasabi()) {
            $this->tasks[] = WasabiStorageTask::class;
        }

        if ($this->jobDataDto->isUploadToGenericS3()) {
            $this->tasks[] = GenericS3StorageTask::class;
        }
    }
}
